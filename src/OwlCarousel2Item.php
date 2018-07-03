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
  private $fileId;

  /**
   * The entity id to be presented when user clicks at the banner.
   *
   * @var int
   */
  private $entityId;

  /**
   * The youtube or vimeo video url.
   *
   * @var string
   */
  private $videoUrl;

  /**
   * The item type.
   *
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
  private $viewId;

  /**
   * The item weight.
   *
   * @var int
   */
  private $weight;

  /**
   * The node view mode.
   *
   * @var string
   */
  private $viewMode;

  /**
   * The image style.
   *
   * @var string
   */
  private $imageStyle;

  /**
   * Display or not the node title.
   *
   * @var bool
   */
  private $displayNodeTitle;

  /**
   * Get the image_style value.
   *
   * @return string
   *   The image_style value.
   */
  public function getImageStyle() {
    return $this->imageStyle;
  }

  /**
   * Set the image_style value.
   *
   * @param string $imageStyle
   *   The image_style.
   */
  public function setImageStyle($imageStyle) {
    $this->imageStyle = $imageStyle;
  }

  /**
   * Get the display_node_title value.
   *
   * @return bool
   *   The display_node_title value.
   */
  public function isDisplayNodeTitle() {
    return $this->displayNodeTitle;
  }

  /**
   * Set the display_node_title value.
   *
   * @param bool $displayNodeTitle
   *   The display_node_title.
   */
  public function setDisplayNodeTitle($displayNodeTitle) {
    $this->displayNodeTitle = $displayNodeTitle;
  }

  /**
   * Get the view_mode value.
   *
   * @return string
   *   The view_mode value.
   */
  public function getViewMode() {
    return $this->viewMode;
  }

  /**
   * Set the view_mode value.
   *
   * @param string $viewMode
   *   The view_mode.
   */
  public function setViewMode($viewMode) {
    $this->viewMode = $viewMode;
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
    return $this->fileId;
  }

  /**
   * Set the file_id value.
   *
   * @param int $fileId
   *   The file_id.
   */
  public function setFileId($fileId) {
    $this->fileId = $fileId;
  }

  /**
   * Get the entity_id value.
   *
   * @return int
   *   The entity_id value.
   */
  public function getEntityId() {
    return $this->entityId;
  }

  /**
   * Set the entity_id value.
   *
   * @param int $entityId
   *   The entity_id.
   */
  public function setEntityId($entityId) {
    $this->entityId = $entityId;
  }

  /**
   * Get the video_url value.
   *
   * @return string
   *   The video_url value.
   */
  public function getVideoUrl() {
    return $this->videoUrl;
  }

  /**
   * Set the video_url value.
   *
   * @param string $videoUrl
   *   The video_url.
   */
  public function setVideoUrl($videoUrl) {
    $this->videoUrl = $videoUrl;
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
    return $this->viewId;
  }

  /**
   * Set the view_id value.
   *
   * @param string $viewId
   *   The view_id.
   */
  public function setViewId($viewId) {
    $this->viewId = $viewId;
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
