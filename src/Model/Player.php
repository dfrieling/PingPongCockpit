<?php

namespace Model;

class Player
{
    private $id, $rfid, $name, $image, $gender;

    public function __construct($id = null, $rfid = null, $name = null, $image = null, $gender = null) {
        $this->id = $id;
        $this->rfid = $rfid;
        $this->name = $name;
        $this->image = $image;
        $this->gender = $gender;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getRfid()
    {
        return $this->rfid;
    }

    /**
     * @param mixed $rfid
     */
    public function setRfid($rfid)
    {
        $this->rfid = $rfid;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return string
     */
    public function toMd5()
    {
        return md5($this->id . $this->rfid . $this->name . $this->image . $this->gender);
    }
}