<?php

namespace Gmu\ContentManager\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Gmu\ContentManager\Entity\CMLanguage;

/**
 * Load GameData
 */
class LoadContentManagerData implements FixtureInterface
{
    /**
     * Load
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $langs = $this->getLangues();
        foreach ($langs as $key => $lg) {            
            $lang = new CMLanguage;
            $lang->setTitle($lg['title']);
            $lang->setIso($lg['iso']);
            $lang->setPublished($lg['published']);
            $lang->setDefault($lg['default']);
            
            $manager->persist($lang);
        }

        $manager->flush();
    }

    public function getLangues(){
        return array(
                array(
                    "title"     => "français",
                    "iso"       => "fr_FR",
                    "published" => 1,
                    "default"   => 1
                ),
                array(
                    "title"     => "anglais",
                    "iso"       => "en_UK",
                    "published" => 1,
                    "default"   => 0
                ),
                array(
                    "title"     => "allemand",
                    "iso"       => "de_DE",
                    "published" => 1,
                    "default"   => 0
                )
            );
    }
}
