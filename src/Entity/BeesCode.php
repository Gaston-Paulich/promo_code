<?php

namespace Drupal\bees_code\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\user\EntityOwnerTrait;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Defines the BeesCode entity.
 *
 * @ContentEntityType(
 *   id = "bees_code",
 *   label = @Translation("Bees Code"),
 *   handlers = {
 *     "list_builder" = "Drupal\\bees_code\\BeesCodeListBuilder",
 *     "access" = "Drupal\\bees_code\\BeesCodeAccessControlHandler",
 *   },
 *   base_table = "bees_code",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "label" = "code",
 *     "created" = "created",
 *     "owner" = "uid",
 *   },
 *   links = {
 *     "canonical" = "/bees_code/{bees_code}",
 *     "collection" = "/admin/content/bees_code",
 *   },
 *   admin_permission = "administer bees code entities",
 * )
 */
class BeesCode extends ContentEntityBase implements EntityOwnerInterface, EntityChangedInterface {
  use EntityOwnerTrait;
  use EntityChangedTrait;

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    // Code field (bees code itself).
    $fields['code'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Bees Code'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 14)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 0,
      ]);

    // Used flag (boolean field to mark if the code has been used).
    $fields['used'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Used'))
      ->setDefaultValue(FALSE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => 1,
      ]);

    return $fields;
  }
}