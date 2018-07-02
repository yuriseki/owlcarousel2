<?php

namespace Drupal\owlcarousel2\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\owlcarousel2\Entity\OwlCarousel2;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class ItemController.
 *
 * @package Drupal\owlcarousel2\Controller
 */
class ItemController extends ControllerBase {

  /**
   * Revmove one item from OwlCarousel2.
   *
   * @param int $owlcarousel2
   *   The OwlCarousel2 id.
   * @param string $item_id
   *   The item id.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   *   The redirect response.
   */
  public function remove($owlcarousel2, $item_id) {
    $carousel = OwlCarousel2::load($owlcarousel2);
    $items    = $carousel->getItems();
    foreach ($items[0] as $key => $value) {
      if ($value['id'] == $item_id) {
        unset($items[0][$key]);

        // Delete the image file.
        if (isset($value['file_id'])) {
          $file = File::load($value['file_id']);
          $file->delete();
        }
        break;
      }
    }

    $carousel->set('items', $items);
    $carousel->save();

    $url = new Url('entity.owlcarousel2.edit_form', [
      'owlcarousel2' => $owlcarousel2,
    ]);

    return new RedirectResponse($url->toString());
  }

}
