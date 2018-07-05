<?php

namespace Drupal\owlcarousel2\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\KeyValueStore\KeyValueExpirableFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\owlcarousel2\Entity\OwlCarousel2;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\owlcarousel2\Util;

/**
 * Provides a 'CarouselBlock' block.
 *
 * @Block(
 *  id = "owlcarousel2_block",
 *  admin_label = @Translation("Carousel block"),
 * )
 */
class CarouselBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The keyValueExpirable service.
   *
   * @var \Drupal\owlcarousel2\Plugin\Block\KeyValueExpirableFactoryInterface
   */
  private $keyValue;

  /**
   * CarouselBlock constructor.
   *
   * @param \Drupal\owlcarousel2\Plugin\Block\KeyValueExpirableFactoryInterface $keyValue
   *   The keyValueExpirable service.
   */
  public function __construct(KeyValueExpirableFactoryInterface $keyValue) {

    $this->keyValue = $keyValue;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $container->get('keyvalue.expirable')
    );
  }

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

    $carousel = FALSE;
    if (isset($this->configuration['carousel_id'])) {
      $carousel = OwlCarousel2::load($this->configuration['carousel_id']);
    }

    $form['carousel_id'] = [
      '#type'          => 'entity_autocomplete',
      '#title'         => $this->t('Carousel'),
      '#description'   => $this->t('Select the carousel'),
      '#target_type'   => 'owlcarousel2',
      '#default_value' => $carousel,
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

    $content                         = Util::getCarouselData($carousel->id());
    $build['#theme']                 = 'owlcarousel2_block';
    $build['#content']['#markup']    = $content;
    $build['#id']                    = 'owlcarousel2-id-' . $carousel->id();
    $build['#attached']['library'][] = 'owlcarousel2/owlcarousel2';

    // In order to allow multiple carousels in the same page, we need to create
    // a key/value pair to pass to JS and apply each configuration to the
    // appropriated carousel.
    // For each carousel block, we will store the configuration using
    // keyvalue.expirable service.
    // The last block call will pass the correct key/value pairs to JS.
    /** @var \Drupal\Core\KeyValueStore\KeyValueExpirableFactoryInterface $key_value */
    $this->keyValue->get('owlcarousel2')->set($carousel->id(), $settings);
    $keyed_settings = $this->keyValue->get('owlcarousel2')->getAll();

    $build['#attached']['drupalSettings']['owlcarousel_settings'] = $keyed_settings;
    return $build;
  }

}
