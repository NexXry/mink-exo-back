<?php

namespace App\Entity;
enum Status: string
{
    case NOT_READY = "pas encore prêt";
    case ON_SALE = "en vente";
    case SOLD_OUT = "VENDU";
}