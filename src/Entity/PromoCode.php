<?php

// **Fase 1: Implementación de la Entidad Drupal**

// Crear la nueva entidad "PromoCode" que se usará para almacenar los códigos promocionales
declare(strict_types=1);

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\user\EntityOwnerTrait;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Define PromoCode entity.
 *
 * @ContentEntityType(
 *   id = "promo_code",
 *   label = @Translation("Promo Code"),
 *   handlers = {
 *     "list_builder" = "Drupal\\promo_code\\PromoCodeListBuilder",
 *   },
 *   base_table = "promo_code",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "label" = "code",
 *     "created" = "created",
 *   },
 * )
 */
class PromoCode extends ContentEntityBase implements EntityOwnerInterface, EntityChangedInterface {
  use EntityOwnerTrait;
  use EntityChangedTrait;

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    // Code field (promo code itself).
    $fields['code'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Promo Code'))
      ->setRequired(TRUE);

    // Used flag (boolean field to mark if the code has been used).
    $fields['used'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Used'))
      ->setDefaultValue(FALSE);

    return $fields;
  }
}