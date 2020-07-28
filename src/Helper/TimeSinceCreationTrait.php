<?php

namespace App\Helper;

/**
 * Trait TimeSinceCreationTrait
 * @package App\Helper
 */
trait TimeSinceCreationTrait
{
    /**
     * @param string $date
     *
     * @return string
     * @throws \Exception
     */
    protected function dateSinceCreation(string $date): string
    {
        $today = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        $date = new \DateTime($date, new \DateTimeZone('Europe/Paris'));
        $interval = $date->diff($today);
        $years = $interval->format('%y');
        $months = $interval->format('%m');
        $days = $interval->format('%d');
        $hours = $interval->format('%h');
        $minutes = $interval->format('%i');
        $seconds = $interval->format('%s');

        if ($years > 0) {
            if ($years == 1) {
                $dateSinceCreation = 'Il y a environ '.$years.' an';
            } else {
                $dateSinceCreation = 'Il y a environ '.$years.' ans';
            }
        } elseif ($months > 0) {
            $dateSinceCreation = 'Il y a '.$months.' mois';
        } elseif ($days > 0) {
            if ($days >= 21 && $days < 28) {
                $dateSinceCreation = 'Il y a 3 semaines';
            } elseif ($days >= 14 && $days < 21) {
                $dateSinceCreation = 'Il y a 2 semaines';
            } elseif ($days == 7 || $days > 7 && $days < 14) {
                $dateSinceCreation = 'Il y a 1 semaine';
            } elseif ($days == 1) {
                $dateSinceCreation = 'Hier';
            } else {
                $dateSinceCreation = 'Il y a '.$days.' jours';
            }
        } elseif ($hours > 0) {
            if ($hours == 1) {
                $dateSinceCreation = 'Il y a '.$hours.' heure';
            } else {
                $dateSinceCreation = 'Il y a '.$hours.' heures';
            }
        } elseif ($minutes > 0) {
            if ($minutes == 1) {
                $dateSinceCreation = 'Il y a '.$minutes.' minute';
            } else {
                $dateSinceCreation = 'Il y a '.$minutes.' minutes';
            }
        } elseif ($seconds > 0) {
            $dateSinceCreation = 'Il y a quelques secondes';
        } else {
            $dateSinceCreation = '';
        }

        return $dateSinceCreation;
    }
}