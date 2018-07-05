<?php

namespace Drupal\owlcarousel2\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\owlcarousel2\Entity\OwlCarousel2;
use Drupal\owlcarousel2\OwlCarousel2Item;

/**
 * Class addVideoForm.
 *
 * @package Drupal\owlcarousel2\Form
 */
class AddVideoForm extends AddItemForm {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'owlcarousel2_add_video_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $owlcarousel2 = NULL, $item_id = NULL) {
    $form['#title'] = $this->t('Carousel | Add Video');

    $form_state->set('owlcarousel2', $owlcarousel2);

    // Check if it is an edition.
    if ($item_id) {
      $carousel = OwlCarousel2::load($owlcarousel2);
      $item     = $carousel->getItem($item_id);

      $form['item_id'] = [
        '#type'  => 'value',
        '#value' => $item_id,
      ];

      $form['weight'] = [
        '#type'  => 'value',
        '#value' => $item['weight'],
      ];
    }

    $form['video_url'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Video Url'),
      '#description'   => $this->t('Youtube or Vimeo Url'),
      '#default_value' => (isset($item['video_url'])) ? $item['video_url'] : '',
      '#required'      => TRUE,
    ];

    $form['item_label_type'] = [
      '#type'        => 'value',
      '#value'       => 'custom_title',
    ];

    $form['item_label'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('Item label'),
      '#description'   => $this->t('Used if you configure the carousel to display text navigation.'),
      '#default_value' => (isset($item['item_label']) && $item['item_label']) ? $item['item_label'] : '',
    ];

    $form += parent::buildForm($form, $form_state, $owlcarousel2, $item_id);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state, OwlCarousel2 $carousel = NULL) {
    $operation       = $form_state->getValue('operation');
    $owlcarousel2_id = $form_state->getStorage()['owlcarousel2'];
    $video_url       = $form_state->getValue('video_url');
    $carousel        = OwlCarousel2::load($owlcarousel2_id);

    $item_array = [
      'type'      => 'video',
      'video_url' => $video_url,
      'item_label' => $form_state->getValue('item_label'),
    ];

    if ($operation == 'add') {
      $item = new OwlCarousel2Item($item_array);
      $carousel->addItem($item);
    }
    else {
      $item_array['id']     = $form_state->getValue('item_id');
      $item_array['weight'] = $form_state->getValue('weight');
      $item                 = new OwlCarousel2Item($item_array);
      $carousel->updateItem($item);
    }

    parent::submitForm($form, $form_state, $carousel);
  }

}
