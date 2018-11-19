<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\ContactCrawler\interfaces;

/**
 *
 * @author jpuchky
 */
interface ISearch {

    public function search($keyword, $language, $num, $from);
}
