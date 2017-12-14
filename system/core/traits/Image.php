<?php

/**
 * @package GPL Cart core
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2015, Iurii Makukh
 * @license https://www.gnu.org/licenses/gpl.html GNU/GPLv3
 */

namespace gplcart\core\traits;

/**
 * CRUD methods for entity images
 */
trait Image
{

    use Translation;

    /**
     * Adds images to an entity
     * @param array $data
     * @param \gplcart\core\models\File $file_model
     * @param \gplcart\core\models\TranslationEntity $translation_entity_model
     * @param string $entity
     * @param null|string $lang
     */
    public function attachImages(&$data, $file_model, $translation_entity_model, $entity, $lang = null)
    {
        if (!empty($data[$entity . '_id'])) {

            $options = array(
                'order' => 'asc',
                'sort' => 'weight',
                'file_type' => 'image',
                'entity' => $entity,
                'entity_id' => $data[$entity . '_id']
            );

            $images = (array) $file_model->getList($options);

            foreach ($images as &$image) {
                $this->attachTranslations($image, $translation_entity_model, 'file', $lang);
            }

            $data['images'] = $images;
        }
    }

    /**
     * Set entity images
     * @param array $data
     * @param \gplcart\core\models\File $file_model
     * @param string $entity
     * @return array
     */
    public function setImages(array &$data, $file_model, $entity)
    {
        if (empty($data['images']) || empty($data[$entity . '_id'])) {
            return array();
        }

        foreach ($data['images'] as &$image) {
            if (empty($image['file_id'])) {
                $image['entity'] = $entity;
                $image['entity_id'] = $data[$entity . '_id'];
                $image['file_id'] = (int) $file_model->add($image);
            } else {
                $file_model->update($image['file_id'], $image);
            }
        }

        return $data['images'];
    }

}
