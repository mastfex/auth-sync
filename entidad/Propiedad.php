<?php

class Propiedad
{
    private $id;
    private $nombre;
    private $value;

    // Constructor
    public function __construct($id, $nombre, $value) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->value = $value;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getValue() {
        return $this->value;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setNombre($nombre) {
        $this->nomnbre = $nombre;
    }

    public function setValue($value) {
        $this->value = $value;
    }
}