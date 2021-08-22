<?php
/**
 * Template Name: Custom-reviews
 *

 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="form">
                <div id="review_form_wrapper">
					<div id="review_form">
						<?php
						$commenter    = wp_get_current_commenter();
						$comment_form = array(
							'title_reply'       => 'Ваш отзыв',
							'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title">',
							'title_reply_after'    => '</h3>',
							'comment_notes_before' => '',
							'comment_notes_after'  => '',
							'label_submit'         => esc_html__( 'Submit', 'woocommerce' ),
							'logged_in_as'         => '',
							'comment_field'        => '',
							'format' 			   => 'xhtml',
						);

						$name_required = (bool) get_option( 'require_name', 1 );
						$fields              = array(
							'author' => array(
								'label'    => __( 'Name', 'woocommerce' ),
								'type'     => 'text',
								'value'    => $commenter['comment_author'],
								'required' => $name_required,
							),
						);

						$comment_form['fields'] = array();

						foreach ( $fields as $key => $field ) {
							$field_html  = '<p class="comment-form-' . esc_attr( $key ) . '">';
							$field_html .= '<label for="' . esc_attr( $key ) . '">';

							//if ( $field['required'] ) {
							//	$field_html .= '&nbsp;<span class="required">*</span>';
							//}

							$field_html .= '</label><input id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" type="' . esc_attr( $field['type'] ) . '" value="' . esc_attr( $field['value'] ) . '" placeholder="'.$field['label'].'" size="30" ' . ( $field['required'] ? 'required' : '' ) . ' /></p>';

							$comment_form['fields'][ $key ] = $field_html;
						}

						$account_page_url = wc_get_page_permalink( 'myaccount' );
						if ( $account_page_url ) {
							/* translators: %s opening and closing link tags respectively */
							$comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf( esc_html__( 'You must be %1$slogged in%2$s to post a review.', 'woocommerce' ), '<a href="' . esc_url( $account_page_url ) . '">', '</a>' ) . '</p>';
						}

						if ( wc_review_ratings_enabled() ) {
							$comment_form['comment_field'] = '<div class="comment-form-rating"><label for="rating">' . esc_html__( 'Оценка', 'woocommerce' ) . '</label><select name="rating" id="rating" required>
								<option value="">' . esc_html__( 'Rate&hellip;', 'woocommerce' ) . '</option>
								<option value="5">' . esc_html__( 'Perfect', 'woocommerce' ) . '</option>
								<option value="4">' . esc_html__( 'Good', 'woocommerce' ) . '</option>
								<option value="3">' . esc_html__( 'Average', 'woocommerce' ) . '</option>
								<option value="2">' . esc_html__( 'Not that bad', 'woocommerce' ) . '</option>
								<option value="1">' . esc_html__( 'Very poor', 'woocommerce' ) . '</option>
							</select></div>';
						}

						$comment_form['comment_field'] .= '<p class="comment-form-comment"><label for="comment"></label><textarea id="comment" name="comment" placeholder="Текст отзыва" cols="45" rows="8" required></textarea></p>';
						add_filter('comment_form_fields', 'kama_reorder_comment_fields' );
						function kama_reorder_comment_fields( $fields ){
							// die(print_r( $fields )); // посмотрим какие поля есть

							$new_fields = array(); // сюда соберем поля в новом порядке

							$myorder = array('author','comment'); // нужный порядок

							foreach( $myorder as $key ){
								$new_fields[ $key ] = $fields[ $key ];
								unset( $fields[ $key ] );
							}

							// если остались еще какие-то поля добавим их в конец
							if( $fields )
								foreach( $fields as $key => $val )
									$new_fields[ $key ] = $val;

							return $new_fields;
						}
						comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
						?>
					</div>
				</div>
            </div>