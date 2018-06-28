<?php

namespace Drupal\owlcarousel2\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\owlcarousel2\Entity\OwlCarousel2;

/**
 * Provides a 'CarouselBlock' block.
 *
 * @Block(
 *  id = "owlcarousel2_block",
 *  admin_label = @Translation("Carousel block"),
 * )
 */
class CarouselBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['carousel_id'] = [
      '#type'          => 'entity_autocomplete',
      '#title'         => $this->t('Carousel'),
      '#description'   => $this->t('Select the carousel'),
      '#target_type'   => 'owlcarousel2',
      '#default_value' => isset($this->configuration['carousel_id']) ? $this->configuration['carousel_id'] : '',
      '#weight'        => '10',
      '#required'      => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['carousel_id'] = $form_state->getValue('carousel_id');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build    = [];
    $carousel = OwlCarousel2::load($this->configuration['carousel_id']);

    if (!$carousel instanceof OwlCarousel2) {
      return [];
    }
    $settings = $carousel->get('settings')->getValue()[0];

    $content                         = owlcarousel2_get_carousel($carousel->id());
    $build['#theme']                 = 'owlcarousel2_block';
    $build['#content']['#markup']    = $content;
    $build['#attached']['library'][] = 'owlcarousel2/owlcarousel2';

    $build['#attached']['drupalSettings']['owlcarousel_settings'] = $settings;

    return $build;
  }

}
