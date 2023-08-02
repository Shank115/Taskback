<?php

namespace Drupal\task_module\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Token Form.
 */
class SettingsForm extends ConfigFormBase
{

  /**
   * Settings Variable.
   */
  const CONFIGNAME = "task_module.settings";

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return "task_module.settings";
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames()
  {
    return [
      static::CONFIGNAME,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $config = $this->config(static::CONFIGNAME);
    $form['subject'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Subject'),
      '#default_value' => $config->get('subject'),
      '#required' => TRUE,
    ];

    $form['textarea'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Content'),
      '#default_value' => $config->get('textarea'),
      '#required' => TRUE,
    ];

    // Token support.
    if (\Drupal::moduleHandler()->moduleExists('token')) {
      $form['tokens'] = [
        '#title' => $this->t('Tokens'),
        '#type' => 'container',
      ];
      $form['tokens']['help'] = [
        '#theme' => 'token_tree_link',
        '#token_types' => [
          'node',
          'site',
        ],
        // '#token_types' => 'all'
        '#global_types' => FALSE,
        '#dialog' => TRUE,
      ];
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $config = $this->config(static::CONFIGNAME);
    $config->set("subject", $form_state->getValue('subject'));
    $config->set("textarea", $form_state->getValue('textarea'));
    $config->save();
  }
}
