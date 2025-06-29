<?php

namespace WebPConvert\Convert\Converters;

use WebPConvert\Convert\Converters\AbstractConverter;
use WebPConvert\Convert\Converters\ConverterTraits\EncodingAutoTrait;
use WebPConvert\Convert\Converters\ConverterTraits\ExecTrait;

use WebPConvert\Convert\Exceptions\ConversionFailed\ConverterNotOperational\SystemRequirementsNotMetException;
use WebPConvert\Convert\Exceptions\ConversionFailedException;

//use WebPConvert\Convert\Exceptions\ConversionFailed\InvalidInput\TargetNotFoundException;

/**
 * Convert images to webp by calling gmagick binary (gm).
 *
 * @package    WebPConvert
 * @author     Bjørn Rosell <it@rosell.dk>
 * @since      Class available since Release 2.0.0
 */
class GraphicsMagick extends AbstractConverter
{
    use ExecTrait;
    use EncodingAutoTrait;

    protected function getUnsupportedDefaultOptions()
    {
        return [
            'near-lossless',
            'size-in-percentage',
        ];
    }

    private function getPath()
    {
        if (defined('WEBPCONVERT_GRAPHICSMAGICK_PATH')) {
            return constant('WEBPCONVERT_GRAPHICSMAGICK_PATH');
        }
        if (!empty(getenv('WEBPCONVERT_GRAPHICSMAGICK_PATH'))) {
            return getenv('WEBPCONVERT_GRAPHICSMAGICK_PATH');
        }
        return 'gm';
    }

    public function isInstalled()
    {
        exec($this->getPath() . ' -version 2>&1', $output, $returnCode);
        return ($returnCode == 0);
    }

    public function getVersion()
    {
        exec($this->getPath() . ' -version 2>&1', $output, $returnCode);
        if (($returnCode == 0) && isset($output[0])) {
            return preg_replace('#http.*#', '', $output[0]);
        }
        return 'unknown';
    }

    // Check if webp delegate is installed
    public function isWebPDelegateInstalled()
    {
        exec($this->getPath() . ' -version 2>&1', $output, $returnCode);
        foreach ($output as $line) {
            if (preg_match('#WebP.*yes#i', $line)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check (general) operationality of imagack converter executable
     *
     * @throws SystemRequirementsNotMetException  if system requirements are not met
     */
    public function checkOperationality()
    {
        $this->checkOperationalityExecTrait();

        if (!$this->isInstalled()) {
            throw new SystemRequirementsNotMetException('gmagick is not installed');
        }
        if (!$this->isWebPDelegateInstalled()) {
            throw new SystemRequirementsNotMetException('webp delegate missing');
        }
    }

    /**
     * Build command line options
     *
     * @return string
     */
    private function createCommandLineOptions()
    {
        // For available webp options, check out:
        // https://github.com/kstep/graphicsmagick/blob/master/coders/webp.c

        $commandArguments = [];

        /*
        if ($this->isQualityDetectionRequiredButFailing()) {
            // Unlike imagick binary, it seems gmagick binary uses a fixed
            // quality (75) when quality is omitted
            // So we cannot simply omit in order to get same quality as source.
            // But perhaps there is another way?
            // Check out #91 - it is perhaps as easy as this: "-define jpeg:preserve-settings"
        }
        */
        $commandArguments[] = '-quality ' . escapeshellarg($this->getCalculatedQuality());

        $options = $this->options;

        // preset
        if (!is_null($options['preset'])) {
            if ($options['preset'] != 'none') {
                $imageHint = $options['preset'];
                switch ($imageHint) {
                    case 'drawing':
                    case 'icon':
                    case 'text':
                        $imageHint = 'graph';
                        $this->logLn(
                            'Note: the preset was mapped to "graph" because graphicsmagick does not support ' .
                            '"drawing", "icon" and "text", but grouped these into one option: "graph".'
                        );
                }
                $commandArguments[] = '-define webp:image-hint=' . escapeshellarg($imageHint);
            }
        }

        // encoding
        if ($options['encoding'] == 'lossless') {
            // Btw:
            // I am not sure if we should set "quality" for lossless.
            // Quality should not apply to lossless, but my tests shows that it does in some way for gmagick
            // setting it low, you get bad visual quality and small filesize. Setting it high, you get the opposite
            // Some claim it is a bad idea to set quality, but I'm not so sure.
            // https://stackoverflow.com/questions/4228027/
            // First, I do not just get bigger images when setting quality, as toc777 does.
            // Secondly, the answer is very old and that bad behaviour is probably fixed by now.
            $commandArguments[] = '-define webp:lossless=true';
        } else {
            $commandArguments[] = '-define webp:lossless=false';
        }

        if ($options['auto-filter'] === true) {
            $commandArguments[] = '-define webp:auto-filter=true';
        }

        if ($options['alpha-quality'] !== 100) {
            $commandArguments[] = '-define webp:alpha-quality=' . strval($options['alpha-quality']);
        }

        if ($options['low-memory']) {
            $commandArguments[] = '-define webp:low-memory=true';
        }

        if ($options['sharp-yuv'] === true) {
            $commandArguments[] = '-define webp:use-sharp-yuv=true';
        }

        if ($options['metadata'] == 'none') {
            $commandArguments[] = '-strip';
        }

        $commandArguments[] = '-define webp:method=' . $options['method'];

        $commandArguments[] = escapeshellarg($this->source);
        $commandArguments[] = escapeshellarg('webp:' . $this->destination);

        return implode(' ', $commandArguments);
    }

    protected function doActualConvert()
    {
        //$this->logLn('Using quality:' . $this->getCalculatedQuality());

        $this->logLn('Version: ' . $this->getVersion());

        $command = $this->getPath() . ' convert ' . $this->createCommandLineOptions() . ' 2>&1';

        $useNice = (($this->options['use-nice']) && self::hasNiceSupport()) ? true : false;
        if ($useNice) {
            $this->logLn('using nice');
            $command = 'nice ' . $command;
        }
        $this->logLn('Executing command: ' . $command);
        exec($command, $output, $returnCode);

        $this->logExecOutput($output);
        if ($returnCode == 0) {
            $this->logLn('success');
        } else {
            $this->logLn('return code: ' . $returnCode);
        }

        if ($returnCode == 127) {
            throw new SystemRequirementsNotMetException('gmagick is not installed');
        }
        if ($returnCode != 0) {
            $this->logLn('command:' . $command);
            $this->logLn('return code:' . $returnCode);
            $this->logLn('output:' . print_r(implode("\n", $output), true));
            throw new SystemRequirementsNotMetException('The exec call failed');
        }
    }
}
