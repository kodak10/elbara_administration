<?php

namespace App\Models\Traits;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

trait ActivityLogger
{
    use LogsActivity;

    /**
     * Définir les attributs à enregistrer dans les logs d'activité.
     * Ici, '*' enregistre tous les attributs de l'entité.
     *
     * @var array
     */
    protected static $logAttributes = ['*'];  // Enregistrer tous les attributs.

    /**
     * Personnalisation de la description de l'événement.
     * Vous pouvez ajouter une logique spécifique à votre application ici.
     *
     * @param string $eventName
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        // Personnalisation des descriptions en fonction du nom du modèle (ex : Company)
        switch (class_basename($this)) {
            case 'Company':
                return $this->getDescriptionForCompanyEvent($eventName);
            case 'DemandeLivreur':
                return $this->getDescriptionForDemandeLivreurEvent($eventName);
            case 'Gare':
                return $this->getDescriptionForGareEvent($eventName);
            case 'Livreur':
                return $this->getDescriptionForLivreurEvent($eventName);
            case 'Order':
                return $this->getDescriptionForOrderEvent($eventName);
            case 'User':
                return $this->getDescriptionForUserEvent($eventName);
            default:
                return ucfirst($eventName) . ' action effectuée sur ' . class_basename($this);
        }
    }

    /**
     * Personnalisation des descriptions pour le modèle Company.
     *
     * @param string $eventName
     * @return string
     */
    public function getDescriptionForCompanyEvent(string $eventName): string
    {
        switch ($eventName) {
            case 'created':
                return "La compagnie '{$this->name}' a été créée.";
            case 'updated':
                return "La compagnie '{$this->name}' a été mise à jour.";
            case 'deleted':
                return "La compagnie '{$this->name}' a été supprimée.";
            case 'restored':
                return "La compagnie '{$this->name}' a été restaurée.";
            case 'forceDeleted':
                return "La compagnie '{$this->name}' a été définitivement supprimée.";
            default:
                return "Une action a été effectuée sur la compagnie '{$this->name}'.";
        }
    }

    /**
     * Personnalisation des descriptions pour le modèle DemandeLivreur.
     *
     * @param string $eventName
     * @return string
     */
    public function getDescriptionForDemandeLivreurEvent(string $eventName): string
    {
        switch ($eventName) {
            case 'created':
                return "La demande de livreur '{$this->id}' a été créée.";
            case 'updated':
                return "La demande de livreur '{$this->id}' a été mise à jour.";
            case 'deleted':
                return "La demande de livreur '{$this->id}' a été supprimée.";
            default:
                return "Une action a été effectuée sur la demande de livreur '{$this->id}'.";
        }
    }

    /**
     * Personnalisation des descriptions pour le modèle Gare.
     *
     * @param string $eventName
     * @return string
     */
    public function getDescriptionForGareEvent(string $eventName): string
    {
        switch ($eventName) {
            case 'created':
                return "La gare '{$this->name}' a été créée.";
            case 'updated':
                return "La gare '{$this->name}' a été mise à jour.";
            case 'deleted':
                return "La gare '{$this->name}' a été supprimée.";
            default:
                return "Une action a été effectuée sur la gare '{$this->name}'.";
        }
    }

    /**
     * Personnalisation des descriptions pour le modèle Livreur.
     *
     * @param string $eventName
     * @return string
     */
    public function getDescriptionForLivreurEvent(string $eventName): string
    {
        switch ($eventName) {
            case 'created':
                return "Le livreur '{$this->name}' a été créé.";
            case 'updated':
                return "Le livreur '{$this->name}' a été mis à jour.";
            case 'deleted':
                return "Le livreur '{$this->name}' a été supprimé.";
            default:
                return "Une action a été effectuée sur le livreur '{$this->name}'.";
        }
    }

    /**
     * Personnalisation des descriptions pour le modèle Order.
     *
     * @param string $eventName
     * @return string
     */
    public function getDescriptionForOrderEvent(string $eventName): string
    {
        switch ($eventName) {
            case 'created':
                return "La commande '{$this->id}' a été créée.";
            case 'updated':
                return "La commande '{$this->id}' a été mise à jour.";
            case 'deleted':
                return "La commande '{$this->id}' a été supprimée.";
            default:
                return "Une action a été effectuée sur la commande '{$this->id}'.";
        }
    }

    /**
     * Personnalisation des descriptions pour le modèle User.
     *
     * @param string $eventName
     * @return string
     */
    public function getDescriptionForUserEvent(string $eventName): string
    {
        switch ($eventName) {
            case 'created':
                return "L'utilisateur '{$this->name}' a été créé.";
            case 'updated':
                return "L'utilisateur '{$this->name}' a été mis à jour.";
            case 'deleted':
                return "L'utilisateur '{$this->name}' a été supprimé.";
            case 'restored':
                return "L'utilisateur '{$this->name}' a été restauré.";
            case 'forceDeleted':
                return "L'utilisateur '{$this->name}' a été définitivement supprimé.";
            default:
                return "Une action a été effectuée sur l'utilisateur '{$this->name}'.";
        }
    }

    /**
     * Définir des options spécifiques pour le logging.
     * Par exemple, vous pouvez enregistrer certains champs ou ignorer certains événements.
     *
     * @return \Spatie\Activitylog\LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])  // Enregistre tous les champs
            ->useLogName('activité') // Nom personnalisé pour les logs
            ->logOnlyDirty(); // Enregistre uniquement les changements
    }
}
