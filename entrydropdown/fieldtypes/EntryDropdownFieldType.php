<?php

namespace Craft;

class EntryDropdownFieldType extends EntriesFieldType
{
    public function getName()
    {
        return Craft::t('Entry Dropdown');
    }

    /**
     * @inheritDoc ISavableComponentType::getSettingsHtml()
     *
     * @return string|null
     */
    public function getSettingsHtml()
    {
        return craft()->templates->render('_components/fieldtypes/elementfieldsettings', array(
            'allowMultipleSources'  => $this->allowMultipleSources,
            'allowLimit'            => false,
            'sources'               => $this->getSourceOptions(),
            'targetLocaleFieldHtml' => $this->getTargetLocaleFieldHtml(),
            'viewModeFieldHtml'     => $this->getViewModeFieldHtml(),
            'settings'              => $this->getSettings(),
            'defaultSelectionLabel' => Craft::t('Enter text here to add a blank value w/ label'),
            'type'                  => $this->getName()
        ));
    }

    /**
     * @inheritDoc IFieldType::prepValueFromPost()
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function prepValueFromPost($value)
    {
        $value = parent::prepValueFromPost($value);

        $value = array_filter($value, function($id) {
            return $id !== '';
        });

        return $value ? $value : null;
    }

    /**
     * @inheritDoc IFieldType::getInputHtml()
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return string
     */
    public function getInputHtml($name, $criteria)
    {
        $settings = $this->getSettings();

        if (! $criteria instanceof ElementCriteriaModel) {
            $criteria = craft()->elements->getCriteria(ElementType::Entry);
            $criteria->id = false;
        }

        $criteria->status = null;
        $criteria->localeEnabled = null;

        $entry = $criteria->first();

        $value = $entry ? $entry->id : null;

        $options = array();

        $selectionLabel = $settings->selectionLabel;

        if ($selectionLabel) {
            $options[''] = $selectionLabel;
        }

        $entries = craft()->elements->getCriteria(ElementType::Entry);

        if (! in_array('*', $settings->sources)) {
            $sectionIds = array();

            foreach ($settings->sources as $source) {
                $sectionIds[] = substr($source, 8);
            }

            $entries->sectionId = $sectionIds;
        }

        foreach ($entries->find() as $element) {
            $options[$element->id] = $element->title;
        }

        return craft()->templates->render('_includes/forms/select', array(
            'name'    => $name.'[]',
            'value'   => $value,
            'options' => $options,
        ));
    }
}
