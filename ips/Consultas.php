<?php

use BaseDatos;

class Consultas
{

    function createTable(BaseDatos $bd){

        $sql = 'create table if not exists aux_paises_ip_mmdb
                (
                    ip_inicio  varchar(15)           not null,
                    ip_fin     varchar(15)           not null,
                    num_inicio int(24) unsigned      not null,
                    num_fin    int(24) unsigned      not null,
                    iso3166    varchar(2) default \'\' not null,
                    nombre     varchar(64)           not null,
                    primary key (ip_inicio, ip_fin)
                )
        collate = utf8_unicode_ci;
    ';

        $expresion = $bd->prepare($sql);
        $expresion->execute();
    }


    function addToTable (BaseDatos $bd, $ip_inicio, $ip_fin, $num_inicio, $num_fin, $iso3166, $nombre){

        try{

            $sql ='INSERT INTO aux_paises_ip_mmdb
                    (ip_inicio, ip_fin, num_inicio,num_fin, iso3166, nombre)
                 VALUES
                    (:ip_inicio, :ip_fin, :num_inicio, :num_fin, :iso3166, :nombre)';

            $expresion = $bd->prepare($sql);
            $expresion->bindValue(':ip_inicio', $ip_inicio);
            $expresion->bindValue(':ip_fin', $ip_fin);
            $expresion->bindValue(':num_inicio', $num_inicio);
            $expresion->bindValue(':num_fin', $num_fin);
            $expresion->bindValue(':iso3166', $iso3166);
            $expresion->bindValue(':nombre', $nombre);

            return $expresion->execute();

        } catch (PDOException $e) {

            echo "Error al insertar" . $e->getMessage();

        }
    }

}