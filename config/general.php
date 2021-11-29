<?php 

return [

  /**
   * Fee required to become a seller
   */
  'seller_fee' => '500',

  /**
   * Commission fee
   */
  'market_fee' => [
    'min' => 0.03,
    'max' => 0.05
  ],
    
  /**
   * Monero daemon
   */
  'monero' => [
    'host' => '127.0.0.1',
    'port' => '28088',
    'user' => 'midlandcity_node',
    'password' => 'lwOyKgNOnN4GrFkUJf1M6qtyUAR6fXYF'
  ],

  /**
   * After how long will orders with the status "waiting" be canceled
   */
  'days_cancel_orders' => 2,

  /**
   * After how long will orders with the status "shipped" be finalized
   */
  'days_complete_orders' => 30,
    
  /**
   * After how many days old completed orders will be deleted
   */
  'days_delete_old_order' => 7,
    
  /**
   * After how many days old messages will be destroyed
   */
  'days_delete_conversations' => 30,

  /**
   * All possible statuses for the order
   */
  'order_status' => [
      'waiting',
      'accepted',
      'shipped',
      'delivered',
      'canceled',
      'disputed'
  ],

  /**
   * Main cases of complaint
   */
  'reporting_causes' => [
      'prohibited_product' => 'prohibited product',
      'scam_attempt' => 'scam attempt',
      'fake_feedback' => 'fake feedback',
      'violates_market_rules' => 'violates market rules',
      'other' => 'other',
  ],

  /**
   * Feedback types
   */
  'feedback_type' => [
      'negative',
      'neutral',
      'positive'
  ],

  /**
   * Order
   */
  'order_by' => [
      'newest',
      'oldest'
  ],

  /**
   * Footer links
   */
  'dread_forum_link' => null,

  'wiki_link' => null

];
