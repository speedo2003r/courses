<?php
/**
 * EdTech WordPress Data Seeder
 *
 * Populates realistic sample Courses, Instructors, Categories, Levels, Blog Posts, AND WordPress Pages.
 *
 * @package EdTech
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EdTech_Seeder {

	/**
	 * Run full seeder with custom counter limits
	 */
	public static function run( $courses_count = 6, $instructors_count = 4, $posts_count = 3, $pages_count = 13 ) {
		self::seed_categories_and_levels();
		$instructor_ids = self::seed_instructors( $instructors_count );
		self::seed_courses( $courses_count, $instructor_ids );
		self::seed_blog_posts( $posts_count );
		self::seed_pages( $pages_count );
		flush_rewrite_rules();
	}

	/**
	 * Seed Categories & Difficulty Levels
	 */
	public static function seed_categories_and_levels() {
		$categories = array(
			'Web Development'   => 'تطوير الويب',
			'UI/UX Design'      => 'تصميم UI/UX',
			'Data Science'      => 'علم البيانات',
			'Digital Marketing' => 'التسويق الرقمي',
			'Business'          => 'إدارة الأعمال',
		);

		foreach ( $categories as $en => $ar ) {
			if ( ! term_exists( $en, 'course_category' ) ) {
				wp_insert_term( $en, 'course_category', array(
					'description' => $ar,
				) );
			}
		}

		$levels = array( 'Beginner', 'Intermediate', 'Advanced' );
		foreach ( $levels as $level ) {
			if ( ! term_exists( $level, 'course_level' ) ) {
				wp_insert_term( $level, 'course_level' );
			}
		}
	}

	/**
	 * Seed WordPress Pages and assign Page Templates
	 */
	public static function seed_pages( $count = 14 ) {
		$pages = array(
			array(
				'title'    => 'Home / الصفحة الرئيسية',
				'slug'     => 'home',
				'template' => 'front-page.php',
			),
			array(
				'title'    => 'Blog / المدونة',
				'slug'     => 'blog',
				'template' => 'home.php',
			),
			array(
				'title'    => 'Catalog / كتالوج الدورات',
				'slug'     => 'catalog',
				'template' => 'archive-course.php',
			),
			array(
				'title'    => 'Learning Paths / مسارات التعلم',
				'slug'     => 'learning-paths',
				'template' => 'page-learning-paths.php',
			),
			array(
				'title'    => 'Free Masterclass / ماستر كلاس مجاني',
				'slug'     => 'free-masterclass',
				'template' => 'page-free-masterclass.php',
			),
			array(
				'title'    => 'About Us / من نحن',
				'slug'     => 'about',
				'template' => 'page-about.php',
			),
			array(
				'title'    => 'FAQ & Support / الأسئلة الشائعة',
				'slug'     => 'faq',
				'template' => 'page-faq.php',
			),
			array(
				'title'    => 'Checkout / الدفع والتسجيل',
				'slug'     => 'checkout',
				'template' => 'page-checkout.php',
			),
			array(
				'title'    => 'Student Dashboard / لوحة الطالب',
				'slug'     => 'student-dashboard',
				'template' => 'page-student-dashboard.php',
			),
			array(
				'title'    => 'Lesson Workspace / مساحة عمل الدرس',
				'slug'     => 'lesson-workspace',
				'template' => 'page-lesson-workspace.php',
			),
			array(
				'title'    => 'Certificates / الشهادات',
				'slug'     => 'certificates',
				'template' => 'page-certificates.php',
			),
			array(
				'title'    => 'Student Settings / إعدادات الحساب',
				'slug'     => 'student-settings',
				'template' => 'page-student-settings.php',
			),
			array(
				'title'    => 'Instructor Dashboard / لوحة المدرب',
				'slug'     => 'instructor-dashboard',
				'template' => 'page-instructor-dashboard.php',
			),
			array(
				'title'    => 'Course Builder / منشئ الدورات',
				'slug'     => 'course-builder',
				'template' => 'page-course-builder.php',
			),
		);

		$limit = min( $count, count( $pages ) );

		for ( $i = 0; $i < $limit; $i++ ) {
			$item = $pages[$i];
			$existing = get_page_by_path( $item['slug'], OBJECT, 'page' );
			if ( ! $existing ) {
				$existing = get_page_by_title( $item['title'], OBJECT, 'page' );
			}

			if ( ! $existing ) {
				$page_id = wp_insert_post( array(
					'post_title'   => $item['title'],
					'post_name'    => $item['slug'],
					'post_status'  => 'publish',
					'post_type'    => 'page',
					'post_content' => 'EdTech Platform System Page',
				) );
				if ( ! empty( $item['template'] ) ) {
					update_post_meta( $page_id, '_wp_page_template', $item['template'] );
				}
			}
		}
	}

	/**
	 * Seed Instructors
	 */
	public static function seed_instructors( $count = 4 ) {
		$data = array(
			array(
				'title' => 'Eng. Tariq Mansour / م. طارق منصور',
				'job'   => 'Senior Full-Stack Architect',
				'img'   => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&auto=format&fit=crop&q=80',
				'audio' => 'assets/media/audio/tariq-intro.mp3',
				'rating'=> '4.9',
				'students' => '12400',
			),
			array(
				'title' => 'Sarah Al-Rashid / سارة الراشد',
				'job'   => 'Lead UI/UX Designer',
				'img'   => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=200&auto=format&fit=crop&q=80',
				'audio' => 'assets/media/audio/sarah-intro.mp3',
				'rating'=> '4.8',
				'students' => '8900',
			),
			array(
				'title' => 'Dr. Omar Farooq / د. عمر فاروق',
				'job'   => 'Data Science & AI Specialist',
				'img'   => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=200&auto=format&fit=crop&q=80',
				'audio' => 'assets/media/audio/omar-intro.mp3',
				'rating'=> '4.9',
				'students' => '21000',
			),
			array(
				'title' => 'Layla Hassan / ليلة حسن',
				'job'   => 'Digital Growth Strategist',
				'img'   => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=200&auto=format&fit=crop&q=80',
				'audio' => 'assets/media/audio/layla-intro.mp3',
				'rating'=> '4.7',
				'students' => '6800',
			),
		);

		$ids = array();
		$limit = min( $count, count( $data ) );

		for ( $i = 0; $i < $limit; $i++ ) {
			$item = $data[$i];
			$existing = get_page_by_title( $item['title'], OBJECT, 'instructor' );
			if ( ! $existing ) {
				$post_id = wp_insert_post( array(
					'post_title'   => $item['title'],
					'post_content' => 'Certified industry expert with over 10 years of experience.',
					'post_status'  => 'publish',
					'post_type'    => 'instructor',
				) );
				update_post_meta( $post_id, '_instructor_title', $item['job'] );
				update_post_meta( $post_id, '_instructor_rating', $item['rating'] );
				update_post_meta( $post_id, '_instructor_students', $item['students'] );
				update_post_meta( $post_id, '_instructor_audio_url', $item['audio'] );
				$ids[] = $post_id;
			} else {
				$ids[] = $existing->ID;
			}
		}

		return $ids;
	}

	/**
	 * Seed Courses
	 */
	public static function seed_courses( $count = 6, $instructor_ids = array() ) {
		$courses_data = array(
			array(
				'title'    => 'Full-Stack Web Development / تطوير الويب المتكامل',
				'cat'      => 'Web Development',
				'price'    => '49',
				'orig'     => '149',
				'badge'    => 'Bestseller',
				'duration' => '12h 30m',
				'lessons'  => '28',
				'rating'   => '4.9',
				'reviews'  => '1240',
			),
			array(
				'title'    => 'Figma UI/UX Design Systems / أنظمة تصميم Figma',
				'cat'      => 'UI/UX Design',
				'price'    => '39',
				'orig'     => '129',
				'badge'    => 'New',
				'duration' => '9h 15m',
				'lessons'  => '22',
				'rating'   => '4.8',
				'reviews'  => '890',
			),
			array(
				'title'    => 'Data Science Dashboards with Python / تحليل البيانات بـ Python',
				'cat'      => 'Data Science',
				'price'    => '59',
				'orig'     => '179',
				'badge'    => 'Hot',
				'duration' => '16h 45m',
				'lessons'  => '35',
				'rating'   => '4.9',
				'reviews'  => '2100',
			),
			array(
				'title'    => 'Digital Growth Marketing / التسويق الرقمي واستراتيجيات النمو',
				'cat'      => 'Digital Marketing',
				'price'    => '29',
				'orig'     => '99',
				'badge'    => 'New',
				'duration' => '7h 20m',
				'lessons'  => '18',
				'rating'   => '4.7',
				'reviews'  => '680',
			),
			array(
				'title'    => 'React 19 Advanced Patterns / الأنماط المتقدمة في React 19',
				'cat'      => 'Web Development',
				'price'    => '69',
				'orig'     => '199',
				'badge'    => 'Bestseller',
				'duration' => '18h 00m',
				'lessons'  => '42',
				'rating'   => '4.8',
				'reviews'  => '1560',
			),
			array(
				'title'    => 'Product Management Essentials / أساسيات إدارة المنتجات الرقمية',
				'cat'      => 'Business',
				'price'    => '45',
				'orig'     => '149',
				'badge'    => 'New',
				'duration' => '10h 30m',
				'lessons'  => '24',
				'rating'   => '4.6',
				'reviews'  => '420',
			),
		);

		$limit = min( $count, count( $courses_data ) );

		for ( $i = 0; $i < $limit; $i++ ) {
			$item = $courses_data[$i];
			$existing = get_page_by_title( $item['title'], OBJECT, 'course' );
			if ( ! $existing ) {
				$post_id = wp_insert_post( array(
					'post_title'   => $item['title'],
					'post_content' => 'Build real-world projects and master high-impact skills with step-by-step guidance.',
					'post_status'  => 'publish',
					'post_type'    => 'course',
				) );

				update_post_meta( $post_id, '_course_price', $item['price'] );
				update_post_meta( $post_id, '_course_price_orig', $item['orig'] );
				update_post_meta( $post_id, '_course_badge', $item['badge'] );
				update_post_meta( $post_id, '_course_duration', $item['duration'] );
				update_post_meta( $post_id, '_course_lessons_count', $item['lessons'] );
				update_post_meta( $post_id, '_course_rating', $item['rating'] );
				update_post_meta( $post_id, '_course_reviews_count', $item['reviews'] );

				$term = get_term_by( 'name', $item['cat'], 'course_category' );
				if ( $term ) {
					wp_set_post_terms( $post_id, array( $term->term_id ), 'course_category' );
				}
			}
		}
	}

	/**
	 * Seed Blog Posts
	 */
	public static function seed_blog_posts( $count = 3 ) {
		$posts = array(
			array(
				'title'   => 'React 19 Complete Breakdown: Server Components & Actions',
				'content' => 'Deep dive into every new React 19 feature with live code examples.',
			),
			array(
				'title'   => 'How to Build Scalable Figma Design Systems',
				'content' => 'From variables and tokens to developer handoff.',
			),
			array(
				'title'   => 'Pandas vs Polars in 2026: Which Performs Better?',
				'content' => 'Performance and memory benchmarking for large-scale data analysis.',
			),
		);

		$limit = min( $count, count( $posts ) );

		for ( $i = 0; $i < $limit; $i++ ) {
			$item = $posts[$i];
			$existing = get_page_by_title( $item['title'], OBJECT, 'post' );
			if ( ! $existing ) {
				wp_insert_post( array(
					'post_title'   => $item['title'],
					'post_content' => $item['content'],
					'post_status'  => 'publish',
					'post_type'    => 'post',
				) );
			}
		}
	}

	/**
	 * Clear all seeded items
	 */
	public static function clear_all() {
		$post_types = array( 'course', 'instructor', 'learning_path', 'page' );
		foreach ( $post_types as $pt ) {
			$posts = get_posts( array( 'post_type' => $pt, 'posts_per_page' => -1 ) );
			foreach ( $posts as $p ) {
				// Don't delete system default pages if any
				wp_delete_post( $p->ID, true );
			}
		}
	}
}
