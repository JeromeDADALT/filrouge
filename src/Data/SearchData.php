<?php


namespace App\Data;


use App\Entity\Day;
use App\Entity\Hour;
use App\Entity\Level;
use App\Entity\Structure;

//je crée une classe qui récupère les données de ma recherche multi critères sous forme de tableaux
class SearchData
{
    /**
     * @var Level[]
     */
    public $level = [];

    /**
     * @var Day[]
     */
    public $day = [];

    /**
     * @var Hour[]
     */
    public $hour = [];

    /**
     * @var Structure[]
     */
    public $structure = [];
}