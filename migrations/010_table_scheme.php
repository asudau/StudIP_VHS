<?php

class TableScheme extends Migration
{
    public function description () {
        return 'expire Table Scheme';
    }


    public function up () {
        
           
        SimpleORMap::expireTableScheme();
    }


    public function down () {
        
    }
    
}
