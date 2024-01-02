<?php

namespace App;

use LogicException;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * Class of configure
 */
class Configure
{
    private string $config = '../configpsr3.yaml';

    private Filesystem $fileSystem;

    /**
     * Path with folder of logs
     *
     * @var string
     */
    public string $FilePath;

    /**
     * Raw array of configure options
     *
     */
    public array $RawConfig;

    public string $LogEMERGENCY = "LogEMERGENCY.log";
    public string $LogALERT = "LogALERT.log";
    public string $LogCRITICAL = "LogCRITICAL.log";
    public string $LogERROR = "LogERROR.log";
    public string $LogWARNING = "LogWARNING.log";
    public string $LogNOTICE = "LogNOTICE.log";
    public string $LogINFO = "LogINFO.log";
    public string $LogDEBUG = "LogDEBUG.log";

    public function __construct()
    {
        $this->fileSystem = new Filesystem();
        $this->getConfigure();
        $this->setFilePath();
        $this->setLogLevelFiles();
    }

    /**
     * Get configure from remote file/array
     *
     * @throws ParseException - problems with configuration
     * @return void
     */
    public function getConfigure(): void
    {
        try {
            if ($this->fileSystem->exists(Path::canonicalize($this->config))) {
                $this->RawConfig = Yaml::parseFile($this->config, Yaml::PARSE_OBJECT);
            } else {
                throw new FileNotFoundException("File configuration not found pls make configpsr3.yaml");
            }
        } catch (ParseException $er) {
            throw new ParseException("Unable to parse the YAML ".$er->getMessage());
        }
    }

    /**
     * Set path to folder with logs
     *
     * @throws LogicException - problems with configuration
     * @return void
     */
    private function setFilePath(): void
    {
        if (array_key_exists("file_path", $this->RawConfig) && is_string($this->RawConfig['file_path']) && !empty($this->RawConfig['file_path'])) {

            $path = Path::canonicalize($this->RawConfig['file_path']);

            if (is_dir($path)) {
                $this->FilePath = Path::canonicalize($this->RawConfig['file_path']);
            } else {
                $this->fileSystem->mkdir($this->RawConfig['file_path']);
                $this->FilePath = Path::canonicalize($this->RawConfig['file_path']);
            }

        } else {
            throw new LogicException("Problem with configuration, {file_path} not found ");
        }
    }

    /**
     * Set other path for level
     *
     * @throws LogicException - mistake in configuration
     * @return void
     */
    private function setLogLevelFiles(): void
    {
        if (array_key_exists("log_level_files", $this->RawConfig)) {

            if (!is_array($this->RawConfig['log_level_files'])) {
                throw new LogicException("Error in configure {log_level_files} this value must be array");
            }

            foreach ($this->RawConfig['log_level_files'] as $item) {

                $key = current(array_keys($item));
                $el = $item[$key];

                switch ($key) {
                    case "EMERGENCY":
                        if (!empty($el)) {
                            $this->LogEMERGENCY = "/".$el;
                        }
                        break;
                    case "ALERT":
                        if (!empty($el)) {
                            $this->LogALERT = "/".$el;
                        }
                        break;
                    case "CRITICAL":
                        if (!empty($el)) {
                            $this->LogCRITICAL = "/".$el;
                        }
                        break;
                    case "ERROR":
                        if (!empty($el)) {
                            $this->LogERROR = "/".$el;
                        }
                        break;
                    case "WARNING":
                        if (!empty($el)) {
                            $this->LogWARNING = "/".$el;
                        }
                        break;
                    case "NOTICE":
                        if (!empty($el)) {
                            $this->LogNOTICE = "/".$el;
                        }
                        break;
                    case "INFO":
                        if (!empty($el)) {
                            $this->LogINFO = "/".$el;
                        }
                        break;
                    case "DEBUG":
                        if (!empty($el)) {
                            $this->LogDEBUG = "/".$el;
                        }
                        break;
                }
            }
        }
    }

}
