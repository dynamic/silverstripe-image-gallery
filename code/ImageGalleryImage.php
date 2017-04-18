<?php

class ImageGalleryImage extends DataObject
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
        'Album' => 'ImageGalleryAlbum',
        'Image' => 'Image',
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName([
            'Sort',
            'AlbumID',
        ]);

        $fields->dataFieldByName('Name')->setDescription('For internal reference only');

        $ImageField = UploadField::create('Image', 'Image')
            ->setFolderName('Uploads/ImageGalleryImages')
            ->setConfig('allowedMaxFileNumber', 1)
            ->setAllowedFileCategories('image')
            ->setAllowedMaxFileNumber(1)
        ;
        $fields->addFieldsToTab('Root.Main', [
            $ImageField,
        ]);

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
