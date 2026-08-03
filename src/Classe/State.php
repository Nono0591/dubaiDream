<?php

namespace App\Classe;

class State
{
   public const STATE = [
        '3' => [
            'label' => 'Commande en cours de préparation',
            'email_subject' => 'Votre commande est en cours de préparation',
            'email_template' => 'order_state_3.html',
        ],
        '4' => [
            'label' => 'Expédiée',
            'email_subject' => 'Votre commande est expédiée',
            'email_template' => 'order_state_4.html',
        ],
        '5' => [
            'label' => 'Commande est annulée',
            'email_subject' => 'Votre commande est annulée',
            'email_template' => 'order_state_5.html',
        ]
    ];

    public static function getState(int $state): string
    {
        return self::STATE[$state]['label'] ?? 'Inconnu';
    }
}