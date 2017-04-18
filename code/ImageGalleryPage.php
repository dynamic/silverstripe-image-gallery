<?php

class ImageGalleryPage extends Page
{
    /**
     * @var array
     */
    private static $has_many = [
        'Albums' => 'ImageGalleryAlbum',
    ];

    /**
     *
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        if ($this->ID) {
            $config = GridFieldConfig_RecordEditor::create();
            if (class_exists('GridFieldOrderableRows')) {
                $config->addComponent(new GridFieldOrderableRows('Sort'));
            }
            if (class_exists('GridFieldBulkUpload')) {
                $config->addComponent(new GridFieldBulkUpload());
                $config->addComponent(new GridFieldBulkManager());
            }
            $config->removeComponentsByType('GridFieldAddExistingAutocompleter');
            $config->removeComponentsByType('GridFieldDeleteAction');
            $config->addComponent(new GridFieldDeleteAction(false));
            $albums = GridField::create('Albums', 'Albums', $this->Albums()->sort('Sort'), $config);

            $fields->addFieldsToTab("Root.Albums", array(
                $albums,
            ));
        }

        return $fields;
    }
}

class ImageGalleryPage_Controller extends Page_Controller
{

}