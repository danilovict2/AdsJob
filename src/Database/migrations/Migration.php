<?php declare(strict_types = 1);

namespace AdsJob\Migrations;

interface Migration{
    public function up() : string;
    public function down() : string;
}