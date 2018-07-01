<?php

namespace Drupal\owlcarousel2;

/**
 * Class OwlCarousel2Item.
 *
 * @package Drupal\owlcarousel2
 */
class OwlCarousel2Item {

  /**
   * The item id, represented by UUID.
   *
   * @var string
   */
  private $id;

  /**
   * The image file id.
   *
   * @var int
   */
  private $file_id;

  /**
   * The entity id to be presented when user clicks at the banner.
   *
   * @var int
   */
  private $entity_id;

  /**
   * The youtube or vimeo video url.
   *
   * @var string
   */
  private $video_url;

  /**
   * The item type.
   *  - custom: for custom item.
   *  - view: for view item.
   *
   * @var string
   */
  private $type;

  /**
   * The view id.
   *
   * @var string
   */
  private $view_id;

  /**
   * The item weight
   *
   * @var int
   */
  private $weight;

  /**
   * The node view mode.
   *
   * @var string
   */
  private $view_mode;

  /**
   * @var string
   */
  private $image_style;

  /** @var  bool */
  private $display_node_title;

  /**
   * Get the image_style value.
   *
   * @return string
   *   The image_style value.
   */
  public function getImageStyle() {
    return $this->image_style;
  }

  /**
   * Set the image_style value.
   *
   * @param string $image_style
   *   The image_style.
   */
  public function setImageStyle($image_style) {
    $this->image_style = $image_style;
  }

  /**
   * Get the display_node_title value.
   *
   * @return bool
   *   The display_node_title value.
   */
  public function isDisplayNodeTitle() {
    return $this->display_node_title;
  }

  /**
   * Set the display_node_title value.
   *
   * @param bool $display_node_title
   *   The display_node_title.
   */
  public function setDisplayNodeTitle($display_node_title) {
    $this->display_node_title = $display_node_title;
  }

  /**
   * Get the view_mode value.
   *
   * @return string
   *   The view_mode value.
   */
  public function getViewMode() {
    return $this->view_mode;
  }

  /**
   * Set the view_mode value.
   *
   * @param string $view_mode
   *   The view_mode.
   */
  public function setViewMode($view_mode) {
    $this->view_mode = $view_mode;
  }

  /**
   * Get the weight value.
   *
   * @return int
   *   The weight value.
   */
  public function getWeight() {
    return $this->weight;
  }

  /**
   * Set the weight value.
   *
   * @param int $weight
   *   The weight.
   */
  public function setWeight($weight) {
    $this->weight = $weight;
  }

  /**
   * Get the id value.
   *
   * @return string
   *   The id value.
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Get the file_id value.
   *
   * @return int
   *   The file_id value.
   */
  public function getFileId() {
    return $this->file_id;
  }

  /**
   * Set the file_id value.
   *
   * @param int $file_id
   *   The file_id.
   */
  public function setFileId($file_id) {
    $this->file_id = $file_id;
  }

  /**
   * Get the entity_id value.
   *
   * @return int
   *   The entity_id value.
   */
  public function getEntityId() {
    return $this->entity_id;
  }

  /**
   * Set the entity_id value.
   *
   * @param int $entity_id
   *   The entity_id.
   */
  public function setEntityId($entity_id) {
    $this->entity_id = $entity_id;
  }

  /**
   * Get the video_url value.
   *
   * @return string
   *   The video_url value.
   */
  public function getVideoUrl() {
    return $this->video_url;
  }

  /**
   * Set the video_url value.
   *
   * @param string $video_url
   *   The video_url.
   */
  public function setVideoUrl($video_url) {
    $this->video_url = $video_url;
  }

  /**
   * Get the type value.
   *
   * @return string
   *   The type value.
   */
  public function getType() {
    return $this->type;
  }

  /**
   * Set the type value.
   *
   * @param string $type
   *   The type.
   */
  public function setType($type) {
    $this->type = $type;
  }

  /**
   * Get the view_id value.
   *
   * @return string
   *   The view_id value.
   */
  public function getViewId() {
    return $this->view_id;
  }

  /**
   * Set the view_id value.
   *
   * @param string $view_id
   *   The view_id.
   */
  public function setViewId($view_id) {
    $this->view_id = $view_id;
  }

  /**
   * The array representation of the item.
   *
   * @return array
   *   The array representation.
   */
  public function getArray() {
    return [
      'id'                 => $this->getId(),
      'type'               => $this->getType(),
      'file_id'            => $this->getFileId(),
      'entity_id'          => $this->getEntityId(),
      'video_url'          => $this->getVideoUrl(),
      'view_id'            => $this->getViewId(),
      'weight'             => $this->getWeight(),
      'view_mode'          => $this->getViewMode(),
      'image_style'        => $this->getImageStyle(),
      'display_node_title' => $this->isDisplayNodeTitle(),
      'view_id'            => $this->getViewId(),
    ];
  }

  /**
   * OwlCarousel2Item constructor.
   *
   * @param array $item_array
   *   The array representation of the item.
   */
  public function __construct(array $item_array) {
    $this->setType(isset($item_array['type']) ? $item_array['type'] : NULL);
    $this->setFileId(isset($item_array['file_id']) ? $item_array['file_id'] : NULL);
    $this->setEntityId(isset($item_array['entity_id']) ? $item_array['entity_id'] : NULL);
    $this->setVideoUrl(isset($item_array['video_url']) ? $item_array['video_url'] : NULL);
    $this->setViewId(isset($item_array['view_id']) ? $item_array['view_id'] : NULL);
    $this->setWeight(isset($item_array['weight']) ? $item_array['weight'] : NULL);
    $this->setViewMode(isset($item_array['view_mode']) ? $item_array['view_mode'] : NULL);
    $this->setImageStyle(isset($item_array['image_style']) ? $item_array['image_style'] : NULL);
    $this->setDisplayNodeTitle(isset($item_array['display_node_title']) ? $item_array['display_node_title'] : FALSE);
    $this->setViewId(isset($item_array['view_id']) ? $item_array['view_id'] : NULL);


    $id = isset($item_array['id']) ? $item_array['id'] : NULL;

    if (is_null($id)) {
      $uuid_service = \Drupal::service('uuid');
      $this->id     = $uuid_service->generate();
    }
    else {
      $this->id = $id;
    }
  }

}
