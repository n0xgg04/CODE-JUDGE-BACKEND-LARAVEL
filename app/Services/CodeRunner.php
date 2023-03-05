<?php

namespace App\Services;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessTimedOutException;

class CodeRunner
{
    public function runCode($language, $code, $input, $timeLimit,$memoryLimit = 1)
    {
        // Define the command to run the code based on the language
        $command = $this->getCommand($language, $code);
        if ($command=='lang_not_found') return ['h2'];
        if(is_array($command)){
            if($command[0]=='ce') return ['ce',$command[1]];
        } 
        // Create a new process to run the command
        $process = new Process($command);
       // file_put_contents('./ab.json',json_encode($process));
        // Set the input for the process
        $process->setInput($input);

        // Set the timeout for the process (in seconds)
        $process->setTimeout($timeLimit);

        try {
            // Run the process and capture the output
            $process->start();
            while ($process->isRunning()) {
                $outputSize = strlen($process->getOutput());
                if ($outputSize > $memoryLimit) {
                    $process->stop();
                    return ['mle',''];  // Memory Limit Exceeded
                }
            }
            $process->wait();
            $output = $process->getOutput();

            if (!$process->isSuccessful() && $process->getStatus() === 9) {
                if($language=='c'||$language=='cpp') unlink($command[0]);
                return ['tle','']; // Time Limit Exceeded
            }

            if (!$process->isSuccessful()) {
                if($language=='c'||$language=='cpp') unlink($command[0]);
                return ['rte','']; // Runtime Error
            }

            // If the process failed, throw an exception with the error message
            if (!empty(trim($process->getErrorOutput()))) {
                if($language=='c'||$language=='cpp') unlink($command[0]);
                return ['ce',$process->getErrorOutput()];
            }
            if($language=='c'||$language=='cpp') unlink($command[0]);
            return ['ok',$output];
        } catch (ProcessTimedOutException $e) {
            if($process->getErrorOutput()) return ['ce',$process->getErrorOutput()];
            return ['tle',''];
        }
    }


    public function compareOutput($output, $expectedOutput)
    {
        $match = strcmp(trim($output), trim($expectedOutput)) === 0;
        return $match;
    }

    private function getCommand($language, $code)
    {
        switch ($language) {
            case 'python':
                return ['python', '-c', $code];
            case 'php':
                return ['php', '-r', $code];
            case 'c':
                $tempFile = tempnam(sys_get_temp_dir(), 'code');
                file_put_contents($tempFile.'.c', $code);
                $make = new Process(['gcc', $tempFile.'.c', '-o', $tempFile . '.out','-lm']);
                $make->setTimeout(15);
                $make->run();
                if($make->getErrorOutput()) return ['ce',$make->getErrorOutput()];
                unlink($tempFile.'.c');
                return [$tempFile . '.out'];
            case 'cpp':
                $tempFile = tempnam(sys_get_temp_dir(), 'code');
                file_put_contents($tempFile.'.cpp', $code);
                $make = new Process(['gcc', $tempFile.'.cpp', '-o', $tempFile . '.out','-lm']);
                $make->setTimeout(15);
                $make->run();
                if($make->getErrorOutput()) return ['ce',$make->getErrorOutput()];
                unlink($tempFile.'.cpp');
                return [$tempFile . '.out'];
            default:
                return 'lang_not_found';
        }
    }
}
