<?php

namespace Drupal\promo_code;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\File\Exception\FileException;

class PromoCodeService {
  protected $entityTypeManager;

  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  // Método para cargar un código válido ingresado por el usuario
  public function createPromoCode(string $code) {
    $promo_code = $this->entityTypeManager->getStorage('promo_code')->create([
      'code' => $code,
      'used' => FALSE,
    ]);
    $promo_code->save();

    return $promo_code;
  }

  // Método para cargar códigos válidos desde un archivo CSV
  public function importCodesFromCsv($file_path) {
    if (($handle = fopen($file_path, 'r')) !== FALSE) {
      while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
        $code = $data[0];
        $existing = $this->loadValidCode($code);
        if (!$existing) {
          $valid_code = $this->entityTypeManager->getStorage('valid_code')->create([
            'code' => $code,
          ]);
          $valid_code->save();
        }
      }
      fclose($handle);
    } else {
      throw new FileException('Unable to open the CSV file.');
    }
  }

  // Método para cargar un código válido de la base de datos
  public function loadValidCode(string $code) {
    $storage = $this->entityTypeManager->getStorage('valid_code');
    $valid_codes = $storage->loadByProperties(['code' => $code]);

    return !empty($valid_codes) ? reset($valid_codes) : NULL;
  }
}
