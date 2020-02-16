<?php


namespace App;


class Spam
{

    public function detect($body)
    {
        $this->detectInvalidKeywords($body);

        return false;
    }

    public function detectInvalidKeywords($body)
    {
        $invalidKeyWords = [
            'yahoo customer support'
            ];

        foreach ($invalidKeyWords as $keyWord)
        {
            if(stripos($body, $keyWord) !== false)
            {
                throw new \Exception('Your reply contains spam');
            }
        }
    }
}
