<?php

class ImageGalleryAlbum extends DataObject
{
    /**
     * @var array
     */
    private static $db = [
        'Name' => 'Varchar(255)',
        'Title' => 'Varchar(255)',
        'Sort' => 'Int',
    ];

    /**
     * @var array
     */
    private static $has_one = [
        'ImageGallery' => 'ImageGalleryPage',
        'Image' => 'Image',
    ];

    /**
     * @var array
     */
    private static $has_many = [
        'Images'=> 'ImageGalleryImage',
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->dataFieldByName('Name')->setDescription('For internal reference only');

        $ImageField = UploadField::create('Image', 'Image')
            ->setFolderName('Uploads/ImageGalleryAlbums')
            ->setConfig('allowedMaxFileNumber', 1)
            ->setAllowedFileCategories('image')
            ->setAllowedMaxFileNumber(1)
        ;
        $fields->addFieldsToTab('Root.Main', [
            $ImageField,
        ]);

        $fields->removeByName([
            'Sort',
            'ImageGalleryID',
            'Images',
        ]);

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
            $albums = GridField::create('Images', 'Images', $this->Images()->sort('Sort'), $config);

            $fields->addFieldsToTab("Root.Images", array(
                $albums,
            ));
        }

        return $fields;
    }

    /**
     * @return ValidationResult
     */
    public function validate()
    {
        $result = parent::validate();

        if (!$this->Name) {
            $result->error('Name is requied before you can save');
        }

        return $result;
    }

    /**
     * @param null $member
     * @return bool|int
     */
    public function canCreate($member = null)
    {
        return Permission::check('Slide_CREATE', 'any', $member);
    }

    /**
     * @param null $member
     * @return bool|int
     */
    public function canEdit($member = null)
    {
        return Permission::check('Slide_EDIT', 'any', $member);
    }

    /**
     * @param null $member
     * @return bool|int
     */
    public function canDelete($member = null)
    {
        return Permission::check('Slide_DELETE', 'any', $member);
    }

    /**
     * @param null $member
     * @return bool
     */
    public function canView($member = null)
    {
        return true;
    }
}