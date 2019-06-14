<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 * widget
 */
if ( ! class_exists( 'TravContactWidget') ) :
class TravContactWidget extends WP_Widget {

	function __construct() {
		// Instantiate the parent object
		parent::__construct( false, 'Travelo Contact Widget' );
	}

	function widget( $args, $instance ) {
		// add custom class contact box
		extract( $args );
		if ( strpos( $before_widget, 'class' ) === false ) {
			$before_widget = str_replace( '>', 'class="'. 'contact-box' . '"', $before_widget );
		}
		else {
			$before_widget = str_replace( 'class="', 'class="'. 'contact-box' . ' ', $before_widget );
		}

		echo wp_kses_post( $before_widget );
		if ( ! empty( $instance['title'] ) ) {
			echo wp_kses_post( $before_title . apply_filters( 'widget_title', $instance['title'] ) . $after_title );
		}
		if ( ! empty( $instance['content'] ) ) {
			echo '<p>' . do_shortcode( $instance['content'] ) . '</p>';
		}
		echo '<address class="contact-details">';
		if ( ! empty( $instance['phone_no'] ) ) {
			echo '<span class="contact-phone"><i class="soap-icon-phone"></i> ' . esc_html( $instance['phone_no'] ) . '</span><br />';
		}
		if ( ! empty( $instance['email'] ) ) {
			echo '<a class="contact-email" href="mailto:' . sanitize_email( $instance['email'] ) . '">' . esc_html( $instance['email'] ) . '</a>';
		}
		echo '</address>';
		echo wp_kses_post( $after_widget );
	}

	function update( $new_instance, $old_instance ) {
		// Save widget options
		$instance = $old_instance;
		if ( ! is_array( $instance ) ) {
			$instance = array();
		}
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['phone_no'] = ( ! empty( $new_instance['phone_no'] ) ) ? strip_tags( $new_instance['phone_no'] ) : '';
		$instance['email'] = ( ! empty( $new_instance['email'] ) ) ? strip_tags( $new_instance['email'] ) : '';
		$instance['content'] = ( ! empty( $new_instance['content'] ) ) ? $new_instance['content'] : '';

		return $instance;
	}

	function form( $instance ) {
		// Output admin widget options form
		$defaults = array( 'title' => 'Contact Title', 'phone_no' => '123-4567', 'email' => 'contact@travelo.com', 'content'=>'Write Content Here.' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>">Title:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" value="<?php echo esc_attr( $instance['title'] ) ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('phone_no') ); ?>">Phone No:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('phone_no') ); ?>" name="<?php echo esc_attr( $this->get_field_name('phone_no') ); ?>" value="<?php echo esc_attr( $instance['phone_no'] ) ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('email') ); ?>">Email:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('email') ); ?>" name="<?php echo esc_attr( $this->get_field_name('email') ); ?>" value="<?php echo esc_attr( $instance['email'] ) ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('content') ); ?>">content:</label>
			<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id('content') ); ?>" name="<?php echo esc_attr( $this->get_field_name('content') ); ?>"><?php echo esc_textarea( $instance['content'] ); ?></textarea>
		</p>

	<?php }
}
endif;

if ( ! class_exists( 'TravTabsWidget') ) :
class TravTabsWidget extends WP_Widget {
	
	function __construct() {
		parent::__construct( false, 'Travelo Tabs Widget' );
	}
	
	function widget($args, $instance) {
		global $data, $post;
		extract($args);

		$posts = $instance['posts'];
		$comments = $instance['comments'];
		$tags_count = $instance['tags'];
		$show_popular_posts = isset($instance['show_popular_posts']) ? 'true' : 'false';
		$show_recent_posts = isset($instance['show_recent_posts']) ? 'true' : 'false';
		$show_comments = isset($instance['show_comments']) ? 'true' : 'false';
		$show_tags = isset($instance['show_tags']) ? 'true' : 'false';
		$orderby = $instance['orderby'];

		if(!$orderby) {
			$orderby = 'Highest Comments';
		}

		echo wp_kses_post( $before_widget );
		?>
		<div class="tab-container box">
			<ul class="tabs full-width">
				<?php if ( $show_recent_posts == 'true' ): ?>
				<li class="active"><a data-toggle="tab" href="#recent-posts"><?php echo __( 'Recent', 'trav' ) ?></a></li>
				<?php endif; ?>
				<?php if( $show_popular_posts == 'true' ): ?>
				<li><a data-toggle="tab" href="#popular-posts"><?php echo __( 'Popular', 'trav' ) ?></a></li>
				<?php endif; ?>
				<?php if($show_comments == 'true'): ?>
				<li><a data-toggle="tab" href="#new-comments"><?php echo __( 'Comment', 'trav' ) ?></a></li>
				<?php endif; ?>
			</ul>
			<div class="tab-content">
				<?php if ( $show_recent_posts == 'true' ): ?>
				<div id="recent-posts" class="tab-pane fade in active">
					<?php $recent_posts = new WP_Query( 'posts_per_page='.$tags_count );
					if ( $recent_posts->have_posts() ): ?>
						<div class="image-box style14">
							<?php while($recent_posts->have_posts()): $recent_posts->the_post(); ?>
							<article class="box">
								<?php if ( has_post_thumbnail() ): ?>
								<figure><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'widget-thumb' ); ?></a></figure>
								<?php endif; ?>
								<div class="details">
									<h5 class="box-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
								</div>
							</article>
							<?php endwhile; ?>
						</div>
					<?php endif; ?>
					<?php wp_reset_postdata(); ?>
				</div>
				<?php endif; ?>
				<?php if( $show_popular_posts == 'true' ): ?>
				<div id="popular-posts" class="tab-pane fade">
					<?php
					if ( $orderby == 'Highest Comments' ) { $order_string = '&orderby=comment_count'; }
					else { $order_string = '&meta_key=trav_count_post_views&orderby=meta_value_num'; }

					$popular_posts = new WP_Query('posts_per_page=' . $posts . $order_string . '&order=DESC');
					if($popular_posts->have_posts()): ?>
						<div class="image-box style14">
							<?php while ( $popular_posts->have_posts() ): $popular_posts->the_post(); ?>
							<article class="box">
								<?php if ( has_post_thumbnail() ): ?>
								<figure><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'widget-thumb' ); ?></a></figure>
								<?php endif; ?>
								<div class="details">
									<h5 class="box-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
								</div>
							</article>
							<?php endwhile; ?>
						</div>
					<?php endif; ?>
					<?php wp_reset_postdata(); ?>
				</div>
				<?php endif; ?>
				<?php if($show_comments == 'true'): ?>
				<div id="new-comments" class="tab-pane fade">
					<?php
					$number = $instance['comments'];
					global $wpdb;
					$recent_comments = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_author_email, comment_date_gmt, comment_approved, comment_type, comment_author_url, user_id, SUBSTRING(comment_content,1,110) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) WHERE comment_approved = '1' AND comment_type = '' AND post_password = '' ORDER BY comment_date_gmt DESC LIMIT $number";
					$the_comments = $wpdb->get_results( $recent_comments );
					if ( ! empty( $the_comments ) ) : ?>
						<div class="image-box style14">
						<?php foreach($the_comments as $comment) { ?>
							<article class="box">
								<figure><a>
									<?php echo trav_get_avatar( array( 'id' => $comment->user_id, 'email' => $comment->comment_author_email, 'size' => 52 ) ); ?>
								</a></figure>
								<div class="details">
									<p><?php echo esc_html( strip_tags($comment->comment_author) ); ?> <?php _e('says', 'trav'); ?>:</p>
									<h5 class="box-title">
										<a href="<?php echo esc_url( get_permalink($comment->ID)  . '#comment-' . $comment->comment_ID ); ?>" title="<?php echo esc_attr( strip_tags( $comment->comment_author ) ); ?> on <?php echo esc_attr( $comment->post_title ); ?>"><?php echo trav_string_limit_words(strip_tags($comment->com_excerpt), 12); ?>...</a>
									</h5>
								</div>
							</article>
						<?php } ?>
						</div>
					<?php endif; ?>
				</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
		echo wp_kses_post( $after_widget );
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['posts'] = $new_instance['posts'];
		$instance['comments'] = $new_instance['comments'];
		$instance['tags'] = $new_instance['tags'];
		$instance['show_popular_posts'] = $new_instance['show_popular_posts'];
		$instance['show_recent_posts'] = $new_instance['show_recent_posts'];
		$instance['show_comments'] = $new_instance['show_comments'];
		$instance['show_tags'] = $new_instance['show_tags'];
		$instance['orderby'] = $new_instance['orderby'];

		return $instance;
	}

	function form($instance) {
		$defaults = array('posts' => 3, 'comments' => '3', 'tags' => 20, 'show_popular_posts' => 'on', 'show_recent_posts' => 'on', 'show_comments' => 'on', 'show_tags' =>  'on', 'orderby' => 'Highest Comments');
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['show_popular_posts'], 'on'); ?> id="<?php echo esc_attr( $this->get_field_id('show_popular_posts') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_popular_posts') ); ?>" /> 
			<label for="<?php echo esc_attr( $this->get_field_id('show_popular_posts') ); ?>"><?php echo __( 'Show popular posts', 'trav' ) ?></label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('orderby') ); ?>"><?php echo __( 'Popular Posts Order By', 'trav' ) ?>:</label> 
			<select id="<?php echo esc_attr( $this->get_field_id('orderby') ); ?>" name="<?php echo esc_attr( $this->get_field_name('orderby') ); ?>" class="widefat" style="width:100%;">
				<option <?php if ('Highest Comments' == $instance['orderby']) echo 'selected="selected"'; ?>><?php echo __( 'Highest Comments', 'trav' ) ?></option>
				<option <?php if ('Highest Views' == $instance['orderby']) echo 'selected="selected"'; ?>><?php echo __( 'Highest Views', 'trav' ) ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('posts') ); ?>"><?php echo __( 'Number of popular posts', 'trav' ) ?>:</label>
			<input class="widefat" style="width: 30px;" id="<?php echo esc_attr( $this->get_field_id('posts') ); ?>" name="<?php echo esc_attr( $this->get_field_name('posts') ); ?>" value="<?php echo esc_attr( $instance['posts'] ) ?>" />
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['show_recent_posts'], 'on'); ?> id="<?php echo esc_attr( $this->get_field_id('show_recent_posts') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_recent_posts') ); ?>" /> 
			<label for="<?php echo esc_attr( $this->get_field_id('show_recent_posts') ); ?>"><?php echo __( 'Show recent posts', 'trav' ) ?></label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('tags') ); ?>"><?php echo __( 'Number of recent posts', 'trav' ) ?>:</label>
			<input class="widefat" style="width: 30px;" id="<?php echo esc_attr( $this->get_field_id('tags') ); ?>" name="<?php echo esc_attr( $this->get_field_name('tags') ); ?>" value="<?php echo esc_attr( $instance['tags'] ) ?>" />
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['show_comments'], 'on'); ?> id="<?php echo esc_attr( $this->get_field_id('show_comments') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_comments') ); ?>" /> 
			<label for="<?php echo esc_attr( $this->get_field_id('show_comments') ); ?>"><?php echo __( 'Show comments', 'trav' ) ?></label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('comments') ); ?>"><?php echo __( 'Number of comments', 'trav' ) ?>:</label>
			<input class="widefat" style="width: 30px;" id="<?php echo esc_attr( $this->get_field_id('comments') ); ?>" name="<?php echo esc_attr( $this->get_field_name('comments') ); ?>" value="<?php echo esc_attr( $instance['comments'] ) ?>" />
		</p>
	<?php }
}
endif;

if ( ! class_exists( 'TravTweetsWidget') ) :
class TravTweetsWidget extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'twitter-box', 'description' => '');
		$control_ops = array('id_base' => 'tweets-widget');
		parent::__construct('tweets-widget', 'Travelo Twitter Widget', $widget_ops, $control_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		$consumer_key = empty($instance['consumer_key'])?'':$instance['consumer_key'];
		$consumer_secret = empty($instance['consumer_secret'])?'':$instance['consumer_secret'];
		$access_token = empty($instance['access_token'])?'':$instance['access_token'];
		$access_token_secret = empty($instance['access_token_secret'])?'':$instance['access_token_secret'];
		$twitter_id = empty($instance['twitter_id'])?'':$instance['twitter_id'];
		$count = empty($instance['count'])?'':(int) $instance['count'];

		echo wp_kses_post( $before_widget );

		if ( ! empty( $title ) ) { echo wp_kses_post( $before_title . esc_html( $title ) . $after_title ); }

		if($twitter_id && $consumer_key && $consumer_secret && $access_token && $access_token_secret && $count) {
		$transName = 'list_tweets_'.$args['widget_id'];
		$cacheTime = 10;
		if(false === ($twitterData = get_transient($transName))) {

			$token = get_option('cfTwitterToken');

			// getting new auth bearer only if we don't have one
			if(!$token) {
				// preparing credentials
				$credentials = $consumer_key . ':' . $consumer_secret;
				$toSend = base64_encode($credentials);

				// http post arguments
				$args = array(
					'method' => 'POST',
					'httpversion' => '1.1',
					'blocking' => true,
					'headers' => array(
						'Authorization' => 'Basic ' . $toSend,
						'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8'
					),
					'body' => array( 'grant_type' => 'client_credentials' )
				);

				add_filter('https_ssl_verify', '__return_false');
				$response = wp_remote_post('https://api.twitter.com/oauth2/token', $args);

				$keys = json_decode(wp_remote_retrieve_body($response));

				if($keys) {
					// saving token to wp_options table
					update_option('cfTwitterToken', $keys->access_token);
					$token = $keys->access_token;
				}
			}
			// we have bearer token wether we obtained it from API or from options
			$args = array(
				'httpversion' => '1.1',
				'blocking' => true,
				'headers' => array(
					'Authorization' => "Bearer $token"
				)
			);

			add_filter('https_ssl_verify', '__return_false');
			$api_url = 'https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name='.$twitter_id.'&count='.$count;
			$response = wp_remote_get($api_url, $args);
			$decoded_json = json_decode(wp_remote_retrieve_body($response), true);

			set_transient($transName, $decoded_json, 60 * $cacheTime);
		}
		$twitter = (array) get_transient($transName);
		if($twitter && is_array($twitter)) {
		?>
		<div class="twitter-holder">
			<ul>
				<?php foreach($twitter as $tweet): ?>
				<li class="tweet">
					<p class="tweet-text">
						<?php
							$latestTweet = $tweet['text'];
							$latestTweet = preg_replace('/http:\/\/([a-z0-9_\.\-\+\&\!\#\~\/\,]+)/i', '&nbsp;<a href="http://$1" target="_blank">http://$1</a>&nbsp;', $latestTweet);
							$latestTweet = preg_replace('/@([a-z0-9_]+)/i', '&nbsp;<a href="http://twitter.com/$1" target="_blank">@$1</a>&nbsp;', $latestTweet);
							echo wp_kses_post( $latestTweet );
						?>
					</p>
					<a class="tweet-date" href="<?php echo esc_url( 'http://twitter.com/' . $tweet['user']['screen_name'] . '/statuses/' . $tweet['id_str'] ) ?>">
						<?php
							$twitterTime = strtotime($tweet['created_at']);
							$timeAgo = $this->ago($twitterTime);
							echo esc_html( $timeAgo );
						?>
					</a>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php }}

		echo wp_kses_post( $after_widget );
	}

	function ago($time) {
	   $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
	   $lengths = array("60","60","24","7","4.35","12","10");

	   $now = time();

		   $difference     = $now - $time;
		   $tense         = "ago";

	   for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
		   $difference /= $lengths[$j];
	   }

	   $difference = round($difference);

	   if($difference != 1) {
		   $periods[$j].= "s";
	   }

	   return "$difference $periods[$j] ago ";
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);
		$instance['consumer_key'] = $new_instance['consumer_key'];
		$instance['consumer_secret'] = $new_instance['consumer_secret'];
		$instance['access_token'] = $new_instance['access_token'];
		$instance['access_token_secret'] = $new_instance['access_token_secret'];
		$instance['twitter_id'] = $new_instance['twitter_id'];
		$instance['count'] = $new_instance['count'];
		delete_option( 'cfTwitterToken' );
		return $instance;
	}

	function form($instance) {
		$defaults = array( 
			'title' => 'Recent Tweets',
			'consumer_key'=>'',
			'consumer_secret'=>'',
			'access_token'=>'',
			'access_token_secret'=>'',
			'twitter_id' => '',
			'count' => 3 );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p><a href="http://dev.twitter.com/apps"><?php echo __( 'Find or Create your Twitter App', 'trav' ); ?></a></p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php echo __( 'Title', 'trav' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" value="<?php echo esc_attr( $instance['title'] ) ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('consumer_key') ); ?>"><?php echo __( 'Consumer Key', 'trav' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('consumer_key') ); ?>" name="<?php echo esc_attr( $this->get_field_name('consumer_key') ); ?>" value="<?php echo esc_attr( $instance['consumer_key'] ) ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('consumer_secret') ); ?>"><?php echo __( 'Consumer Secret', 'trav' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('consumer_secret') ); ?>" name="<?php echo esc_attr( $this->get_field_name('consumer_secret') ); ?>" value="<?php echo esc_attr( $instance['consumer_secret'] ) ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('access_token') ); ?>"><?php echo __( 'Access Token', 'trav' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('access_token') ); ?>" name="<?php echo esc_attr( $this->get_field_name('access_token') ); ?>" value="<?php echo esc_attr( $instance['access_token'] ) ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('access_token_secret') ); ?>"><?php echo __( 'Access Token Secret', 'trav' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('access_token_secret') ); ?>" name="<?php echo esc_attr( $this->get_field_name('access_token_secret') ); ?>" value="<?php echo esc_attr( $instance['access_token_secret'] ) ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('twitter_id') ); ?>"><?php echo __( 'Twitter ID', 'trav' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('twitter_id') ); ?>" name="<?php echo esc_attr( $this->get_field_name('twitter_id') ); ?>" value="<?php echo esc_attr( $instance['twitter_id'] ) ?>" />
		</p>

			<label for="<?php echo esc_attr( $this->get_field_id('count') ); ?>"><?php echo __( 'Number of Tweets', 'trav' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('count') ); ?>" name="<?php echo esc_attr( $this->get_field_name('count') ); ?>" value="<?php echo esc_attr( $instance['count'] ) ?>" />
		</p>

	<?php
	}
}
endif;

if ( ! class_exists( 'TravNewsWidget' ) ) :
class TravNewsWidget extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'travel-news', 'description' => 'A Sidebar widget to display latest post entries in your sidebar' );
		$control_ops = array('id_base' => 'news-widget');
		parent::__construct( 'news-widget', 'Travelo News Widget', $widget_ops, $control_ops );
	}

	function widget($args, $instance) {
		extract($args);
		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
		$count = empty($instance['count']) ? 2 : $instance['count'];
		$cat = empty($instance['cat']) ? '' : $instance['cat'];

		echo wp_kses_post( $before_widget );

		if ( ! empty( $title ) ) { echo wp_kses_post( $before_title . esc_html( $title ) . $after_title ); };

		$news_loop = new WP_Query( "cat=".$cat."&posts_per_page=".$count."&post_type=post" );
		if ( $news_loop->have_posts() ) :
			echo '<ul class="travel-news">';
			while ($news_loop->have_posts()) : $news_loop->the_post();
				$image = get_the_post_thumbnail( get_the_ID(), 'widget-thumb' );
				if ( empty( $image ) ) $class=' class="no-post-thumbnail"';
				echo '<li>';
				echo '<div class="thumb">';
				echo '<a href="' . esc_url( get_permalink() ) . '" title="' . esc_attr( get_the_title() ) . '"' . $class . '>';
				echo wp_kses_post( $image );
				echo '</a></div>';
				echo '<div class="description">';
				echo '<h5 class="title">';
				echo '<a href="' . esc_url( get_permalink() ) . '" title="' . esc_attr( get_the_title() ) . '">' . esc_html( get_the_title() ) . '</a></h5>';
				$brief = apply_filters( 'the_content', get_post_field( 'post_content', get_the_ID() ) );
				$brief = wp_trim_words( $brief, 10, '' );
				echo wp_kses_post( '<p>' . $brief . '</p>' );
				echo '<span class="date">'; the_date(); echo '</span>';
				echo '</div>';
				echo '</li>';
			endwhile;
			echo "</ul>";
			wp_reset_postdata();
		endif;
		echo wp_kses_post( $after_widget );
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['count'] = strip_tags($new_instance['count']);
		$instance['cat'] = implode(',',$new_instance['cat']);
		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'count' => '', 'cat' => '', 'excerpt'=>'' ) );
		$title = strip_tags($instance['title']);
		$count = strip_tags($instance['count']); ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php echo __( 'Title', 'trav' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('count') ); ?>"><?php echo __( 'How many entries do you want to display', 'trav' ); ?>: </label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id('count') ); ?>" name="<?php echo esc_attr( $this->get_field_name('count') ); ?>">
				<?php for ( $i = 1; $i <= 20; $i++ ) {
					$selected = "";
					if($count == $i) $selected = 'selected="selected"';
					echo "<option {$selected} value='" . esc_attr( $i ) . "'>" . esc_html( $i ) . "</option>";
				} ?>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('cat') ); ?>"><?php echo __( 'Choose the categories you want to display (multiple selection possible)', 'trav' ); ?>: </label>
			<select multiple="multiple" class="widefat" id="<?php echo esc_attr( $this->get_field_id('cat') ); ?>" name="<?php echo esc_attr( $this->get_field_name('cat') . '[]' ); ?>">
			<?php $entries = get_categories( 'orderby=name&hide_empty=0' );
			$cats = explode( ',', $instance['cat'] );
			foreach ( $entries as $entry ) {
				$term_id = $entry->term_id;
				$selected = '';
				if ( in_array( $term_id, $cats ) ) { $selected = "selected='selected'"; }
				echo "<option {$selected} value='" . esc_attr( $term_id ) . "'>" . esc_html( $entry->name ) . "</option>";
			} ?>
			</select>
		</p>
	<?php
	}
}
endif;

if ( ! class_exists( 'TravSocialLinksWidget' ) ) :
class TravSocialLinksWidget extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'social_links', 'description' => '');
		$control_ops = array('id_base' => 'social_links-widget');
		parent::__construct('social_links-widget', 'Travelo Social Links Widget', $widget_ops, $control_ops);
	}

	function widget($args, $instance) {
		extract($args);
		if ( ! empty( $instance['title'] ) ) {
		$title = apply_filters('widget_title', $instance['title']);
		}

		echo wp_kses_post( $before_widget );

		if ( ! empty( $title ) ) { echo wp_kses_post( $before_title . esc_html( $title ) . $after_title ); } ?>

		<ul class="social-icons clearfix<?php if ( isset( $instance['style'] ) && ( $instance['style'] == 'style2' ) ) echo ' style2'; ?>">
		<?php if ( ! empty( $instance['rss_link'] ) ) : ?>
		<li class="rss"><a title="rss" href="<?php echo esc_url( $instance['rss_link'] ); ?>" target="<?php echo esc_attr( $instance['linktarget'] ); ?>" data-toggle="tooltip"><i class="fa fa-rss"></i></a></li>
		<?php endif; ?>

		<?php if ( ! empty( $instance['twitter_link'] ) ) : ?>
		<li class="twitter"><a title="twitter" href="<?php echo esc_url( $instance['twitter_link'] ); ?>" target="<?php echo esc_attr( $instance['linktarget'] ); ?>" data-toggle="tooltip"><i class="soap-icon-twitter"></i></a></li>
		<?php endif; ?>

		<?php if ( ! empty( $instance['google_link'] ) ) : ?>
		<li class="googleplus"><a title="googleplus" href="<?php echo esc_url( $instance['google_link'] ); ?>" target="<?php echo esc_attr( $instance['linktarget'] ); ?>" data-toggle="tooltip"><i class="soap-icon-googleplus"></i></a></li>
		<?php endif; ?>

		<?php if ( ! empty( $instance['facebook_link'] ) ) : ?>
		<li class="facebook"><a title="facebook" href="<?php echo esc_url( $instance['facebook_link'] ); ?>" target="<?php echo esc_attr( $instance['linktarget'] ); ?>" data-toggle="tooltip"><i class="soap-icon-facebook"></i></a></li>
		<?php endif; ?>

		<?php if ( ! empty( $instance['linkedin_link'] ) ) : ?>
		<li class="linkedin"><a title="linkedin" href="<?php echo esc_url( $instance['linkedin_link'] ); ?>" target="<?php echo esc_attr( $instance['linktarget'] ); ?>" data-toggle="tooltip"><i class="soap-icon-linkedin"></i></a></li>
		<?php endif; ?>

		<?php if ( ! empty( $instance['youtube_link'] ) ) : ?>
		<li class="youtube"><a title="youtube" href="<?php echo esc_url( $instance['youtube_link'] ); ?>" target="<?php echo esc_attr( $instance['linktarget'] ); ?>" data-toggle="tooltip"><i class="soap-icon-youtube"></i></a></li>
		<?php endif; ?>

		<?php if ( ! empty( $instance['pinterest_link'] ) ) : ?>
		<li class="pinterest"><a title="pinterest" href="<?php echo esc_url( $instance['pinterest_link'] ); ?>" target="<?php echo esc_attr( $instance['linktarget'] ); ?>" data-toggle="tooltip"><i class="soap-icon-pinterest"></i></a></li>
		<?php endif; ?>

		<?php if ( ! empty( $instance['vimeo_link'] ) ) : ?>
		<li class="vimeo"><a title="vimeo" href="<?php echo esc_url( $instance['vimeo_link'] ); ?>" target="<?php echo esc_attr( $instance['linktarget'] ); ?>" data-toggle="tooltip"><i class="soap-icon-vimeo"></i></a></li>
		<?php endif; ?>

		<?php if ( ! empty( $instance['skype_link'] ) ) : ?>
		<li class="skype"><a title="skype" href="<?php echo ( $instance['skype_link'] ); ?>" data-toggle="tooltip"><i class="soap-icon-skype"></i></a></li>
		<?php endif; ?>

		<?php if ( ! empty( $instance['instagram_link'] ) ) : ?>
		<li class="instagram"><a title="instagram" href="<?php echo esc_url( $instance['instagram_link'] ); ?>" target="<?php echo esc_attr( $instance['linktarget'] ); ?>" data-toggle="tooltip"><i class="soap-icon-instagram"></i></a></li>
		<?php endif; ?>

		<?php if ( ! empty( $instance['dribbble_link'] ) ) : ?>
		<li class="dribble"><a title="dribble" href="<?php echo esc_url( $instance['dribbble_link'] ); ?>" target="<?php echo esc_attr( $instance['linktarget'] ); ?>" data-toggle="tooltip"><i class="soap-icon-dribble"></i></a></li>
		<?php endif; ?>

		<?php if ( ! empty( $instance['flickr_link'] ) ) : ?>
		<li class="flickr"><a title="flickr" href="<?php echo esc_url( $instance['flickr_link'] ); ?>" target="<?php echo esc_attr( $instance['linktarget'] ); ?>" data-toggle="tooltip"><i class="soap-icon-flickr"></i></a></li>
		<?php endif; ?>

		<?php if ( ! empty( $instance['tumblr_link'] ) ) : ?>
		<li class="tumblr"><a title="tumblr" href="<?php echo esc_url( $instance['tumblr_link'] ); ?>" target="<?php echo esc_attr( $instance['linktarget'] ); ?>" data-toggle="tooltip"><i class="soap-icon-tumblr"></i></a></li>
		<?php endif; ?>

		<?php if ( ! empty( $instance['behance_link'] ) ) : ?>
		<li class="behance"><a title="behance" href="<?php echo esc_url( $instance['behance_link'] ); ?>" target="<?php echo esc_attr( $instance['linktarget'] ); ?>" data-toggle="tooltip"><i class="soap-icon-behance"></i></a></li>
		<?php endif; ?>

		<?php if ( ! empty( $instance['deviantart_link'] ) ) : ?>
		<li class="deviantart"><a title="deviantart" href="<?php echo esc_url( $instance['deviantart_link'] ); ?>" target="<?php echo esc_attr( $instance['linktarget'] ); ?>" data-toggle="tooltip"><i class="soap-icon-deviantart"></i></a></li>
		<?php endif; ?>

		<?php if ( ! empty( $instance['myspace_link'] ) ) : ?>
		<li class="myspace"><a title="myspace" href="<?php echo esc_url( $instance['myspace_link'] ); ?>" target="<?php echo esc_attr( $instance['linktarget'] ); ?>" data-toggle="tooltip"><i class="soap-icon-myspace"></i></a></li>
		<?php endif; ?>

		<?php if ( ! empty( $instance['soundcloud_link'] ) ) : ?>
		<li class="soundcloud"><a title="soundcloud" href="<?php echo esc_url( $instance['soundcloud_link'] ); ?>" target="<?php echo esc_attr( $instance['linktarget'] ); ?>" data-toggle="tooltip"><i class="soap-icon-soundcloud"></i></a></li>
		<?php endif; ?>

		<?php if ( ! empty( $instance['stumbleupon_link'] ) ) : ?>
		<li class="stumbleupon"><a title="stumbleupon" href="<?php echo esc_url( $instance['stumbleupon_link'] ); ?>" target="<?php echo esc_attr( $instance['linktarget'] ); ?>" data-toggle="tooltip"><i class="soap-icon-stumbleupon"></i></a></li>
		<?php endif; ?>

		</ul>
		<?php
		echo wp_kses_post( $after_widget );
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;

		$instance['title'] = $new_instance['title'];
		$instance['style'] = $new_instance['style'];
		$instance['linktarget'] = $new_instance['linktarget'];
		$instance['rss_link'] = $new_instance['rss_link'];
		$instance['twitter_link'] = $new_instance['twitter_link'];
		$instance['google_link'] = $new_instance['google_link'];
		$instance['facebook_link'] = $new_instance['facebook_link'];
		$instance['linkedin_link'] = $new_instance['linkedin_link'];
		$instance['youtube_link'] = $new_instance['youtube_link'];
		$instance['pinterest_link'] = $new_instance['pinterest_link'];
		$instance['vimeo_link'] = $new_instance['vimeo_link'];
		$instance['skype_link'] = $new_instance['skype_link'];
		$instance['instagram_link'] = $new_instance['instagram_link'];
		$instance['dribbble_link'] = $new_instance['dribbble_link'];
		$instance['flickr_link'] = $new_instance['flickr_link'];
		$instance['tumblr_link'] = $new_instance['tumblr_link'];
		$instance['behance_link'] = $new_instance['behance_link'];
		$instance['deviantart_link'] = $new_instance['deviantart_link'];
		$instance['myspace_link'] = $new_instance['myspace_link'];
		$instance['soundcloud_link'] = $new_instance['soundcloud_link'];
		$instance['stumbleupon_link'] = $new_instance['stumbleupon_link'];

		return $instance;
	}

	function form($instance) {
		$defaults = array(
			'title'             => 'Get Social',
			'style'             => 'style1',
			'linktarget'        => '',
			'rss_link'          => '',
			'twitter_link'      => '',
			'google_link'       => '',
			'facebook_link'     => '',
			'linkedin_link'     => '',
			'youtube_link'      => '',
			'pinterest_link'    => '',
			'vimeo_link'        => '',
			'skype_link'        => '',
			'instagram_link'    => '',
			'dribbble_link'     => '',
			'flickr_link'       => '',
			'tumblr_link'       => '',
			'behance_link'      => '',
			'deviantart_link'   => '',
			'myspace_link'      => '',
			'soundcloud_link'   => '',
			'stumbleupon_link'  => '',
			);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>">Title:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" value="<?php echo esc_attr( $instance['title'] ) ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('style') ); ?>"><?php echo __( 'Please select social links style', 'trav' ); ?>: </label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id('style') ); ?>" name="<?php echo esc_attr( $this->get_field_name('style') ); ?>">
				<?php $styles = array( 'style1' => 'Rectangle', 'style2' => 'Circle' );
				foreach ( $styles as $key=>$value ) {
					if($style == $key) $selected = 'selected="selected"';
					echo "<option {$selected} value='" . esc_attr( $key ) . "'>" . esc_html( $value ) . "</option>";
				} ?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('linktarget') ); ?>">Link Target:</label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id('linktarget') ); ?>" name="<?php echo esc_attr( $this->get_field_name('linktarget') ); ?>">
				<?php
				$linktargets = array( '_blank' => '_blank', '_self' => '_self' );
				foreach ( $linktargets as $key=>$value ) {
					$selected = "";
					if ( $linktarget == $key ) $selected = 'selected="selected"';
					echo "<option {$selected} value='" . esc_attr( $key ) . "'>" . esc_html( $value ) . "</option>";
				} ?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('rss_link') ); ?>">RSS Link:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('rss_link') ); ?>" name="<?php echo esc_attr( $this->get_field_name('rss_link') ); ?>" value="<?php echo esc_attr( $instance['rss_link'] ) ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('twitter_link') ); ?>">Twitter Link:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('twitter_link') ); ?>" name="<?php echo esc_attr( $this->get_field_name('twitter_link') ); ?>" value="<?php echo esc_attr( $instance['twitter_link'] ) ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('google_link') ); ?>">Google+ Link:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('google_link') ); ?>" name="<?php echo esc_attr( $this->get_field_name('google_link') ); ?>" value="<?php echo esc_attr( $instance['google_link'] ) ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('facebook_link') ); ?>">Facebook Link:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('facebook_link') ); ?>" name="<?php echo esc_attr( $this->get_field_name('facebook_link') ); ?>" value="<?php echo esc_attr( $instance['facebook_link'] ) ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('linkedin_link') ); ?>">LinkedIn Link:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('linkedin_link') ); ?>" name="<?php echo esc_attr( $this->get_field_name('linkedin_link') ); ?>" value="<?php echo esc_attr( $instance['linkedin_link'] ) ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('youtube_link') ); ?>">YouTube Link:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('youtube_link') ); ?>" name="<?php echo esc_attr( $this->get_field_name('youtube_link') ); ?>" value="<?php echo esc_attr( $instance['youtube_link'] ) ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('pinterest_link') ); ?>">Pinterest Link:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('pinterest_link') ); ?>" name="<?php echo esc_attr( $this->get_field_name('pinterest_link') ); ?>" value="<?php echo esc_attr( $instance['pinterest_link'] ) ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('vimeo_link') ); ?>">Vimeo Link:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('vimeo_link') ); ?>" name="<?php echo esc_attr( $this->get_field_name('vimeo_link') ); ?>" value="<?php echo esc_attr( $instance['vimeo_link'] ) ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('skype_link') ); ?>">Skype Link:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('skype_link') ); ?>" name="<?php echo esc_attr( $this->get_field_name('skype_link') ); ?>" value="<?php echo esc_attr( $instance['skype_link'] ) ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('instagram_link') ); ?>">Instagram Link:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('instagram_link') ); ?>" name="<?php echo esc_attr( $this->get_field_name('instagram_link') ); ?>" value="<?php echo esc_attr( $instance['instagram_link'] ) ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('dribbble_link') ); ?>">Dribbble Link:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('dribbble_link') ); ?>" name="<?php echo esc_attr( $this->get_field_name('dribbble_link') ); ?>" value="<?php echo esc_attr( $instance['dribbble_link'] ) ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('flickr_link') ); ?>">Flickr Link:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('flickr_link') ); ?>" name="<?php echo esc_attr( $this->get_field_name('flickr_link') ); ?>" value="<?php echo esc_attr( $instance['flickr_link'] ) ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('tumblr_link') ); ?>">Tumblr Link:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('tumblr_link') ); ?>" name="<?php echo esc_attr( $this->get_field_name('tumblr_link') ); ?>" value="<?php echo esc_attr( $instance['tumblr_link'] ) ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('behance_link') ); ?>">Behance Link:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('behance_link') ); ?>" name="<?php echo esc_attr( $this->get_field_name('behance_link') ); ?>" value="<?php echo esc_attr( $instance['behance_link'] ) ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('deviantart_link') ); ?>">Deviantart Link:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('deviantart_link') ); ?>" name="<?php echo esc_attr( $this->get_field_name('deviantart_link') ); ?>" value="<?php echo esc_attr( $instance['deviantart_link'] ) ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('myspace_link') ); ?>">Myspace Link:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('myspace_link') ); ?>" name="<?php echo esc_attr( $this->get_field_name('myspace_link') ); ?>" value="<?php echo esc_attr( $instance['myspace_link'] ) ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('soundcloud_link') ); ?>">Soundcloud Link:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('soundcloud_link') ); ?>" name="<?php echo esc_attr( $this->get_field_name('soundcloud_link') ); ?>" value="<?php echo esc_attr( $instance['soundcloud_link'] ) ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('stumbleupon_link') ); ?>">Stumbleupon Link:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('stumbleupon_link') ); ?>" name="<?php echo esc_attr( $this->get_field_name('stumbleupon_link') ); ?>" value="<?php echo esc_attr( $instance['stumbleupon_link'] ) ?>" />
		</p>
	<?php
	}
}
endif;

if ( ! class_exists ( 'TravNavMenuWidget' ) ) :
class TravNavMenuWidget extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_nav_menu', 'description' => '');
		$control_ops = array('id_base' => 'trav-nav_menu-widget');
		parent::__construct('trav-nav_menu-widget', 'Travelo Custom Menu', $widget_ops, $control_ops);
	}

	function widget($args, $instance) {
		extract( $args );
		// Get menu
		$nav_menu = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;

		if ( !$nav_menu )
			return;

		/** This filter is documented in wp-includes/default-widgets.php */
		$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		echo wp_kses_post( $before_widget );

		if ( ! empty($instance['title']) ) echo wp_kses_post( $before_title . esc_html( $instance['title'] ) . $after_title );

		add_filter( 'nav_menu_css_class', 'trav_add_one_half_nav_class', 10, 2 );
		wp_nav_menu( array( 'fallback_cb' => '', 'menu' => $nav_menu, 'menu_class' => 'row' ) );
		remove_filter( 'nav_menu_css_class', 'trav_add_one_half_nav_class' );

		echo wp_kses_post( $after_widget );
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		if ( ! empty( $new_instance['title'] ) ) {
			$instance['title'] = strip_tags( stripslashes($new_instance['title']) );
		}
		if ( ! empty( $new_instance['nav_menu'] ) ) {
			$instance['nav_menu'] = (int) $new_instance['nav_menu'];
		}
		return $instance;
	}

	/**
	 * @param array $instance
	 */
	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		$nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';

		// Get menus
		$menus = wp_get_nav_menus();

		// If no menus exists, direct the user to go and create some.
		?>
		<p class="nav-menu-widget-no-menus-message" <?php if ( ! empty( $menus ) ) { echo ' style="display:none" '; } ?>>
			<?php
			if ( isset( $GLOBALS['wp_customize'] ) && $GLOBALS['wp_customize'] instanceof WP_Customize_Manager ) {
				$url = 'javascript: wp.customize.panel( "nav_menus" ).focus();';
			} else {
				$url = admin_url( 'nav-menus.php' );
			}
			?>
			<?php echo sprintf( __( 'No menus have been created yet. <a href="%s">Create some</a>.' ), esc_attr( $url ) ); ?>
		</p>
		<div class="nav-menu-widget-form-controls" <?php if ( empty( $menus ) ) { echo ' style="display:none" '; } ?>>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ) ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>"/>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'nav_menu' ); ?>"><?php _e( 'Select Menu:' ); ?></label>
				<select id="<?php echo $this->get_field_id( 'nav_menu' ); ?>" name="<?php echo $this->get_field_name( 'nav_menu' ); ?>">
					<option value="0"><?php _e( '&mdash; Select &mdash;' ); ?></option>
					<?php foreach ( $menus as $menu ) : ?>
						<option value="<?php echo esc_attr( $menu->term_id ); ?>" <?php selected( $nav_menu, $menu->term_id ); ?>>
							<?php echo esc_html( $menu->name ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</p>
		</div>
		<?php
	}
}
endif;

if ( ! class_exists( 'TravSimilarAccWidget') ) :
class TravSimilarAccWidget extends WP_Widget {
	
	function __construct() {
		parent::__construct( false, 'Travelo Similar Accommodations' );
	}

	function widget($args, $instance) {
		extract($args);
		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
		$count = empty($instance['count']) ? 3 : $instance['count'];
		$image_size = array( 64, 64 );

		echo wp_kses_post( $before_widget );

		if ( ! empty( $title ) ) { echo wp_kses_post( $before_title . esc_html( $title ) . $after_title ); };

		echo do_shortcode('[similar_accommodations count="' . $count . '"]' );

		echo wp_kses_post( $after_widget );
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['count'] = strip_tags($new_instance['count']);
		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'count' => '', 'cat' => '', 'excerpt'=>'' ) );
		$title = strip_tags($instance['title']);
		$count = strip_tags($instance['count']); ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php echo __( 'Title', 'trav' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('count') ); ?>"><?php echo __( 'How many entries do you want to display', 'trav' ); ?>: </label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id('count') ); ?>" name="<?php echo esc_attr( $this->get_field_name('count') ); ?>">
				<?php for ( $i = 1; $i <= 10; $i++ ) {
					$selected = "";
					if($count == $i) $selected = 'selected="selected"';
					echo "<option {$selected} value='" . esc_attr( $i ) . "'>" . esc_html( $i ) . "</option>";
				} ?>
			</select>
		</p>

	<?php
	}
}
endif;

if ( ! class_exists( 'TravLatestTourWidget') ) :
class TravLatestTourWidget extends WP_Widget {
	
	function __construct() {
		parent::__construct( false, 'Travelo Latest Tours' );
	}

	function widget($args, $instance) {
		extract($args);
		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
		$count = empty($instance['count']) ? 3 : $instance['count'];
		$image_size = array( 64, 64 );

		echo wp_kses_post( $before_widget );

		if ( ! empty( $title ) ) { echo wp_kses_post( $before_title . esc_html( $title ) . $after_title ); };

		echo do_shortcode('[latest_tours count="' . $count . '"]' );

		echo wp_kses_post( $after_widget );
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['count'] = strip_tags($new_instance['count']);
		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'count' => '', 'cat' => '', 'excerpt'=>'' ) );
		$title = strip_tags($instance['title']);
		$count = strip_tags($instance['count']); ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php echo __( 'Title', 'trav' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('count') ); ?>"><?php echo __( 'How many entries do you want to display', 'trav' ); ?>: </label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id('count') ); ?>" name="<?php echo esc_attr( $this->get_field_name('count') ); ?>">
				<?php for ( $i = 1; $i <= 10; $i++ ) {
					$selected = "";
					if($count == $i) $selected = 'selected="selected"';
					echo "<option {$selected} value='" . esc_attr( $i ) . "'>" . esc_html( $i ) . "</option>";
				} ?>
			</select>
		</p>

	<?php
	}
}
endif;

function trav_add_one_half_nav_class( $classes, $item ) {
	$classes[] = 'col-xs-6';
	return $classes;
}

function trav_register_widgets() {
	register_widget( 'TravContactWidget' );
	register_widget( 'TravTabsWidget' );
	register_widget( 'TravTweetsWidget' );
	register_widget( 'TravNewsWidget' );
	register_widget( 'TravSocialLinksWidget' );
	register_widget( 'TravNavMenuWidget' );
	register_widget( 'TravSimilarAccWidget' );
	register_widget( 'TravLatestTourWidget' );
}

add_action( 'widgets_init', 'trav_register_widgets' );
?>