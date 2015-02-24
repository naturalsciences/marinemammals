<?php
namespace AppBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class DateTimeToDateTimeArrayTransformer implements DataTransformerInterface
{
    public function transform($datetime)
    {
        if(null !== $datetime)
        {
            $date = clone $datetime;
            $date->setTime(12, 0, 0);

            $time = clone $datetime;
            $time->setDate(1970, 1, 1);
            //$date=$datetime->format("H:i");
        }
        else
        {
            $date = null;
            $time = null;
        }

        $result = array(
            'date' => $date,
            'time' => $time
        );

        return $result;
    }

    public function reverseTransform($array)
    {
        $date = $array['date'];
        $time = $array['time'];

        if(null == $date || null == $time)
            return null;
        $date->setTimezone(new \DateTimeZone('Europe/Paris'));
        $date->setTime($time->format('G'), $time->format('i'));

        return $date;
    }
}