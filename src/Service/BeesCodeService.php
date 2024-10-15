<?php

namespace Drupal\bees_code;

use Drupal\Core\Entity\EntityTypeManagerInterface;

class BeesCodeService {
  protected $entityTypeManager;

  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  // Método para validar y marcar un código como 'usado'
  public function validateAndMarkCodeAsUsed(string $code) {
    $valid_code = $this->loadValidCode($code);
    if ($valid_code && !$valid_code->get('used')->value) {
      $valid_code->set('used', TRUE);
      $valid_code->save();
      return TRUE;
    }
    return FALSE;
  }

  // Método para cargar un código válido de la base de datos y validar que exista
  protected function loadValidCode(string $code) {
    $storage = $->entityTypeManager->getStorage('bees_code');
    $valid_codes = $storage->loadByProperties(['code' => $code]);

    if (empty($valid_codes)) {
      throw new \Exception('El código ingresado no es válido.');
    }

    return reset($valid_codes);
  }
}