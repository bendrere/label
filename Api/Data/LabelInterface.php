<?php

namespace Ndr\Label\Api\Data;

interface LabelInterface
{

    const ID = 'label_id';
    const NAME = 'name';
    const LABEL_IMAGE = 'label_image';
    const CONDITIONS_SERIALIZED = 'conditions_serialized';


    public function getLabelImage();

    public function getId();

    public function setLabelImage($label_image);

    public function getConditionsFieldSetId();

    public function setConditionsSerialized($condition);

    public function getConditionsSerialized();
}
