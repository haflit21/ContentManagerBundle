<?php

namespace ContentManagerBundle\ContentManagerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use ContentManagerBundle\ContentManagerBundle\Entity\CMLanguage;

/**
 * Load ContentManager
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
        // Set default languages
        $langs = $this->getLangues();
        foreach ($langs as $key => $lg) {
            $lang = new CMLanguage;

            $lang->setTitle($lg['title']);
            $lang->setIso($lg['iso']);
            $lang->setPublished($lg['published']);
            $lang->setDefaultLan($lg['default']);

            $manager->persist($lang);
        }

        $manager->flush();
    }

    public function getLangues(){
        return array(
                array(
                    "title"     => "français",
                    "iso"       => "fr-FR",
                    "published" => 1,
                    "default"   => 1
                ),
                array(
                    "title"     => "anglais",
                    "iso"       => "en-UK",
                    "published" => 1,
                    "default"   => 0
                ),
                array(
                    "title"     => "allemand",
                    "iso"       => "de-DE",
                    "published" => 1,
                    "default"   => 0
                )
            );
    }
}
