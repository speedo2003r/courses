<?php
/**
 * EdTech WordPress Data Seeder
 *
 * Populates realistic sample Courses, Instructors, Categories, Levels, Blog Posts, AND WordPress Pages.
 * All content is bilingual (English in standard fields, Arabic in _title_ar / _content_ar meta).
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
		self::seed_learning_paths();
		self::seed_blog_posts( $posts_count );
		self::seed_pages( $pages_count );
		self::seed_faq();
		self::seed_testimonials();
		self::seed_team();
		flush_rewrite_rules();
	}

	/**
	 * Seed Categories & Difficulty Levels
	 */
	public static function seed_categories_and_levels() {
		$categories = array(
			'Web Development'   => 'تطوير الويب',
			'UI/UX Design'      => 'تصميم الواجهات',
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

		$levels = array(
			'Beginner'     => 'مبتدئ',
			'Intermediate'  => 'متوسط',
			'Advanced'      => 'متقدم',
		);
		foreach ( $levels as $en => $ar ) {
			if ( ! term_exists( $en, 'course_level' ) ) {
				wp_insert_term( $en, 'course_level', array(
					'description' => $ar,
				) );
			}
		}
	}

	/**
	 * Seed WordPress Pages and assign Page Templates
	 */
	public static function seed_pages( $count = 14 ) {
		$pages = array(
			array(
				'title'    => 'Home',
				'title_ar' => 'الصفحة الرئيسية',
				'slug'     => 'home',
				'template' => '',
				'role'     => 'front',
			),
			array(
				'title'    => 'Blog',
				'title_ar' => 'المدونة',
				'slug'     => 'blog',
				'template' => '',
				'role'     => 'posts',
			),
			array(
				'title'    => 'Learning Paths',
				'title_ar' => 'مسارات التعلم',
				'slug'     => 'learning-paths',
				'template' => 'page-learning-paths.php',
			),
			array(
				'title'    => 'Free Masterclass',
				'title_ar' => 'ماستر كلاس مجاني',
				'slug'     => 'free-masterclass',
				'template' => 'page-free-masterclass.php',
			),
			array(
				'title'    => 'About Us',
				'title_ar' => 'من نحن',
				'slug'     => 'about',
				'template' => 'page-about.php',
			),
			array(
				'title'    => 'FAQ & Support',
				'title_ar' => 'الأسئلة الشائعة',
				'slug'     => 'faq',
				'template' => 'page-faq.php',
			),
			array(
				'title'    => 'Checkout',
				'title_ar' => 'الدفع والتسجيل',
				'slug'     => 'checkout',
				'template' => 'page-checkout.php',
			),
			array(
				'title'    => 'Student Dashboard',
				'title_ar' => 'لوحة الطالب',
				'slug'     => 'student-dashboard',
				'template' => 'page-student-dashboard.php',
			),
			array(
				'title'    => 'Lesson Workspace',
				'title_ar' => 'مساحة عمل الدرس',
				'slug'     => 'lesson-workspace',
				'template' => 'page-lesson-workspace.php',
			),
			array(
				'title'    => 'Certificates',
				'title_ar' => 'الشهادات',
				'slug'     => 'certificates',
				'template' => 'page-certificates.php',
			),
			array(
				'title'    => 'Student Settings',
				'title_ar' => 'إعدادات الحساب',
				'slug'     => 'student-settings',
				'template' => 'page-student-settings.php',
			),
			array(
				'title'    => 'Instructor Dashboard',
				'title_ar' => 'لوحة المدرب',
				'slug'     => 'instructor-dashboard',
				'template' => 'page-instructor-dashboard.php',
			),
			array(
				'title'    => 'Course Builder',
				'title_ar' => 'منشئ الدورات',
				'slug'     => 'course-builder',
				'template' => 'page-course-builder.php',
			),
		);

		$limit    = min( $count, count( $pages ) );
		$front_id = 0;
		$posts_id = 0;

		for ( $i = 0; $i < $limit; $i++ ) {
			$item     = $pages[$i];
			$existing = get_page_by_path( $item['slug'], OBJECT, 'page' );

			if ( ! $existing ) {
				$page_id = wp_insert_post( array(
					'post_title'   => $item['title'],
					'post_name'    => $item['slug'],
					'post_status'  => 'publish',
					'post_type'    => 'page',
					'post_content' => '',
				) );
			} else {
				$page_id = $existing->ID;
			}

			if ( ! empty( $item['title_ar'] ) ) {
				update_post_meta( $page_id, '_title_ar', $item['title_ar'] );
			}
			if ( ! empty( $item['template'] ) ) {
				update_post_meta( $page_id, '_wp_page_template', $item['template'] );
			}
			if ( isset( $item['role'] ) && 'front' === $item['role'] ) {
				$front_id = $page_id;
			}
			if ( isset( $item['role'] ) && 'posts' === $item['role'] ) {
				$posts_id = $page_id;
			}
		}

		if ( $front_id ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $front_id );
		}
		if ( $posts_id ) {
			update_option( 'page_for_posts', $posts_id );
		}
	}

	/**
	 * Seed Instructors
	 */
	public static function seed_instructors( $count = 4 ) {
		$data = array(
			array(
				'title'    => 'Eng. Tariq Mansour',
				'title_ar' => 'م. طارق منصور',
				'job'      => 'Senior Full-Stack Architect',
				'job_ar'   => 'مهندس برمجيات متكامل أول',
				'content'  => 'Certified industry expert with over 10 years of experience building large-scale web applications. Previously at Stripe and Shopify.',
				'content_ar' => 'خبير معتمد في الصناعة بخبرة تزيد عن 10 سنوات في بناء تطبيقات ويب واسعة النطاق. عمل سابقاً في Stripe و Shopify.',
				'img'      => 'assets/img/instructors/tariq.jpg',
				'audio'    => 'assets/media/audio/tariq-intro.mp3',
				'rating'   => '4.9',
				'students'  => '12400',
			),
			array(
				'title'    => 'Sarah Al-Rashid',
				'title_ar' => 'سارة الراشد',
				'job'      => 'Lead UI/UX Designer',
				'job_ar'   => 'مصممة واجهات وتجربة مستخدم أولى',
				'content'  => 'Award-winning designer specializing in design systems and accessible interfaces. 8+ years at top agencies.',
				'content_ar' => 'مصممة حائزة على جوائز متخصصة في أنظمة التصميم والواجهات سهلة الوصول. أكثر من 8 سنوات في وكالات رائدة.',
				'img'      => 'assets/img/instructors/sarah.jpg',
				'audio'    => 'assets/media/audio/sarah-intro.mp3',
				'rating'   => '4.8',
				'students'  => '8900',
			),
			array(
				'title'    => 'Dr. Omar Farooq',
				'title_ar' => 'د. عمر فاروق',
				'job'      => 'Data Science & AI Specialist',
				'job_ar'   => 'أخصائي علم البيانات والذكاء الاصطناعي',
				'content'  => 'PhD in Machine Learning with 12+ years teaching. Published researcher in NLP and computer vision.',
				'content_ar' => 'دكتوراه في تعلم الآلة وخبرة تدريسية تزيد عن 12 عاماً. باحث منشور في معالجة اللغات الطبيعية والرؤية الحاسوبية.',
				'img'      => 'assets/img/instructors/omar.jpg',
				'audio'    => 'assets/media/audio/omar-intro.mp3',
				'rating'   => '4.9',
				'students'  => '21000',
			),
			array(
				'title'    => 'Layla Hassan',
				'title_ar' => 'ليلى حسن',
				'job'      => 'Digital Growth Strategist',
				'job_ar'   => 'استراتيجية النمو الرقمي',
				'content'  => 'Growth marketing leader who scaled 3 startups from zero to 1M+ users. Speaker at major conferences.',
				'content_ar' => 'قائدة في تسويق النمو قادت 3 شركات ناشئة من الصفر إلى أكثر من مليون مستخدم. متحدثة في مؤتمرات كبرى.',
				'img'      => 'assets/img/instructors/layla.jpg',
				'audio'    => 'assets/media/audio/layla-intro.mp3',
				'rating'   => '4.7',
				'students'  => '6800',
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
					'post_content' => $item['content'],
					'post_status'  => 'publish',
					'post_type'    => 'instructor',
				) );
				update_post_meta( $post_id, '_title_ar', $item['title_ar'] );
				update_post_meta( $post_id, '_content_ar', $item['content_ar'] );
				update_post_meta( $post_id, '_instructor_title', $item['job'] );
				update_post_meta( $post_id, '_instructor_title_ar', $item['job_ar'] );
				update_post_meta( $post_id, '_instructor_rating', $item['rating'] );
				update_post_meta( $post_id, '_instructor_students', $item['students'] );
				update_post_meta( $post_id, '_instructor_audio_url', $item['audio'] );
				update_post_meta( $post_id, '_thumbnail_url', $item['img'] );
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
				'title'      => 'Full-Stack Web Development',
				'title_ar'   => 'تطوير الويب المتكامل',
				'content'    => 'Build 5 real-world full-stack apps from scratch. Master HTML5, CSS3, JavaScript ES2025, React 19, Node.js, and databases. Includes deployment to production.',
				'content_ar' => 'ابنِ 5 تطبيقات ويب متكاملة من الصفر. أتقن HTML5 و CSS3 و JavaScript ES2025 و React 19 و Node.js وقواعد البيانات. يشمل النشر على بيئة الإنتاج.',
				'cat'        => 'Web Development',
				'price'      => '49',
				'orig'       => '149',
				'badge'      => 'Bestseller',
				'duration'   => '12h 30m',
				'lessons'    => '28',
				'rating'     => '4.9',
				'reviews'    => '1240',
				'syllabus'   => "HTML5 Semantic Structure\nCSS Grid & Flexbox Mastery\nJavaScript ES2025 Fundamentals\nReact 19 Components & Hooks\nNode.js REST API Design\nDatabase Modeling with PostgreSQL\nAuthentication & Security\nDeployment to Production",
				'syllabus_ar' => "هيكلية HTML5 الدلالية\nإتقان CSS Grid و Flexbox\nأساسيات JavaScript ES2025\nمكونات React 19 والخطافات\nتصميم واجهات Node.js REST\nنمذجة قواعد البيانات بـ PostgreSQL\nالمصادقة والأمان\nالنشر على بيئة الإنتاج",
				'outcomes'   => "Build production-ready full-stack applications\nImplement responsive UI with modern CSS\nDesign and consume REST APIs\nDeploy apps to cloud platforms",
				'outcomes_ar' => "بناء تطبيقات ويب متكاملة جاهزة للإنتاج\nتنفيذ واجهات مستجيبة بـ CSS الحديث\nتصميم واستهلاك واجهات REST\nنشر التطبيقات على منصات السحابة",
				'skills'     => "HTML5, CSS3, JavaScript, React 19, Node.js, PostgreSQL, Git, Docker",
				'skills_ar'  => "HTML5, CSS3, JavaScript, React 19, Node.js, PostgreSQL, Git, Docker",
			),
			array(
				'title'      => 'Figma UI/UX Design Systems',
				'title_ar'   => 'أنظمة تصميم Figma',
				'content'    => 'Master Figma from zero to advanced. Build scalable design systems, tokens, and component libraries. Perfect for designers and developers.',
				'content_ar' => 'أتقن Figma من الصفر إلى المستوى المتقدم. ابني أنظمة تصميم قابلة للتوسع ورموز ومكتبات مكونات. مثالي للمصممين والمطورين.',
				'cat'        => 'UI/UX Design',
				'price'      => '39',
				'orig'       => '129',
				'badge'      => 'New',
				'duration'   => '9h 15m',
				'lessons'    => '22',
				'rating'     => '4.8',
				'reviews'    => '890',
				'syllabus'   => "Figma Interface Fundamentals\nAuto Layout & Components\nDesign Tokens & Variables\nBuilding a Design System\nPrototyping & Interactions\nDeveloper Handoff Workflow\nAccessibility Audits",
				'syllabus_ar' => "أساسيات واجهة Figma\nالطبقة التلقائية والمكونات\nرموز التصميم والمتغيرات\nبناء نظام تصميم\nالنماذج الأولية والتفاعلات\nتسليم العمل للمطورين\nتدقيق الوصول",
				'outcomes'   => "Build a scalable design system in Figma\nCreate reusable component libraries\nMaster auto layout and variants\nStreamline designer-developer handoff",
				'outcomes_ar' => "بناء نظام تصميم قابل للتوسع في Figma\nإنشاء مكتبات مكونات قابلة لإعادة الاستخدام\nإتقان الطبقة التلقائية والمتغيرات\nتبسيط تسليم العمل بين المصمم والمطور",
				'skills'     => "Figma, Design Systems, Auto Layout, Prototyping, Accessibility",
				'skills_ar'  => "Figma, أنظمة التصميم, الطبقة التلقائية, النماذج, الوصول",
			),
			array(
				'title'      => 'Data Science Dashboards with Python',
				'title_ar'   => 'تحليل البيانات بـ Python',
				'content'    => 'Build interactive data dashboards using Python, Pandas, and Plotly. Learn data cleaning, visualization, and storytelling.',
				'content_ar' => 'ابنِ لوحات بيانات تفاعلية باستخدام Python و Pandas و Plotly. تعلم تنظيف البيانات والتصور وسرد القصص.',
				'cat'        => 'Data Science',
				'price'      => '59',
				'orig'       => '179',
				'badge'      => 'Hot',
				'duration'   => '16h 45m',
				'lessons'    => '35',
				'rating'     => '4.9',
				'reviews'    => '2100',
				'syllabus'   => "Python Data Ecosystem Overview\nPandas & NumPy Essentials\nData Cleaning & Transformation\nStatistical Analysis\nPlotly & Dash Interactives\nBuilding a Live Dashboard\nDeploying with Streamlit",
				'syllabus_ar' => "نظرة عامة على نظام Python للبيانات\nأساسيات Pandas و NumPy\nتنظيف البيانات وتحويلها\nالتحليل الإحصائي\nلوحات Plotly و Dash التفاعلية\nبناء لوحة بيانات حية\nالنشر بـ Streamlit",
				'outcomes'   => "Build interactive data dashboards\nClean and transform real-world datasets\nApply statistical analysis techniques\nDeploy dashboards to the web",
				'outcomes_ar' => "بناء لوحات بيانات تفاعلية\nتنظيف وتحويل مجموعات بيانات حقيقية\nتطبيق تقنيات التحليل الإحصائي\nنشر اللوحات على الويب",
				'skills'     => "Python, Pandas, NumPy, Plotly, Streamlit, Statistics",
				'skills_ar'  => "Python, Pandas, NumPy, Plotly, Streamlit, الإحصاء",
			),
			array(
				'title'      => 'Digital Growth Marketing',
				'title_ar'   => 'التسويق الرقمي واستراتيجيات النمو',
				'content'    => 'Learn the frameworks top startups use to grow. SEO, paid ads, email funnels, A/B testing, and analytics — all in one practical course.',
				'content_ar' => 'تعلم الأطر التي تستخدمها الشركات الناشئة للنمو. SEO والإعلانات المدفوعة وقمع البريد واختبار A/B والتحليلات — في دورة عملية واحدة.',
				'cat'        => 'Digital Marketing',
				'price'      => '29',
				'orig'       => '99',
				'badge'      => 'New',
				'duration'   => '7h 20m',
				'lessons'    => '18',
				'rating'     => '4.7',
				'reviews'    => '680',
				'syllabus'   => "Growth Marketing Fundamentals\nSEO & Content Strategy\nGoogle & Meta Ads\nEmail Automation Funnels\nA/B Testing Frameworks\nAnalytics & Attribution\nRetention & Loyalty",
				'syllabus_ar' => "أساسيات تسويق النمو\nاستراتيجية SEO والمحتوى\nإعلانات Google و Meta\nقمع البريد الآلي\nأطر اختبار A/B\nالتحليلات والإسناد\nالاحتفاظ والولاء",
				'outcomes'   => "Design and execute growth marketing funnels\nRun profitable paid ad campaigns\nImplement A/B testing pipelines\nMeasure and optimize with analytics",
				'outcomes_ar' => "تصميم وتنفيذ قمع تسويق النمو\nإدارة حملات إعلانية مدفوعة مربحة\nتنفيذ خطوط اختبار A/B\nقياس وتحسين الأداء بالتحليلات",
				'skills'     => "SEO, Google Ads, Meta Ads, Email Marketing, A/B Testing, Analytics",
				'skills_ar'  => "SEO, إعلانات Google, إعلانات Meta, تسويق البريد, اختبار A/B, التحليلات",
			),
			array(
				'title'      => 'React 19 Advanced Patterns',
				'title_ar'   => 'الأنماط المتقدمة في React 19',
				'content'    => 'Deep dive into React 19 Server Components, Suspense, Actions, and the new compiler. Build production-grade apps with the latest patterns.',
				'content_ar' => 'غوص عميق في مكونات React 19 الخادمية و Suspense و Actions والمترجم الجديد. ابني تطبيقات بإنتاجية عالية بأحدث الأنماط.',
				'cat'        => 'Web Development',
				'price'      => '69',
				'orig'       => '199',
				'badge'      => 'Bestseller',
				'duration'   => '18h 00m',
				'lessons'    => '42',
				'rating'     => '4.8',
				'reviews'    => '1560',
				'syllabus'   => "React 19 Architecture Overview\nServer Components Deep Dive\nSuspense & Streaming\nActions & Form Integration\nThe React Compiler\nState Management Patterns\nPerformance Optimization",
				'syllabus_ar' => "نظرة على معمارية React 19\nغوص عميق في المكونات الخادمية\nSuspense والبث\nActions وتكامل النماذج\nمترجم React\nأنماط إدارة الحالة\nتحسين الأداء",
				'outcomes'   => "Build apps with React 19 Server Components\nMaster Suspense and streaming patterns\nImplement Actions for forms and mutations\nOptimize rendering performance",
				'outcomes_ar' => "بناء تطبيقات بمكونات React 19 الخادمية\nإتقان أنماط Suspense والبث\nتنفيذ Actions للنماذج والتعديلات\nتحسين أداء العرض",
				'skills'     => "React 19, Server Components, Suspense, Actions, Next.js",
				'skills_ar'  => "React 19, المكونات الخادمية, Suspense, Actions, Next.js",
			),
			array(
				'title'      => 'Product Management Essentials',
				'title_ar'   => 'أساسيات إدارة المنتجات الرقمية',
				'content'    => 'Learn the full product lifecycle: discovery, roadmap planning, prioritization, user research, and stakeholder communication.',
				'content_ar' => 'تعلم دورة حياة المنتج الكاملة: الاكتشاف وتخطيط خارطة الطريق والأولوية وبحث المستخدم والتواصل مع أصحاب المصلحة.',
				'cat'        => 'Business',
				'price'      => '45',
				'orig'       => '149',
				'badge'      => 'New',
				'duration'   => '10h 30m',
				'lessons'    => '24',
				'rating'     => '4.6',
				'reviews'    => '420',
				'syllabus'   => "Product Management Fundamentals\nDiscovery & User Research\nRoadmapping & Prioritization\nMetrics & KPIs\nStakeholder Communication\nAgile & Sprint Planning\nGo-to-Market Strategy",
				'syllabus_ar' => "أساسيات إدارة المنتج\nالاكتشاف وبحث المستخدم\nخارطة الطريق والأولوية\nالمقاييس والمؤشرات\nالتواصل مع أصحاب المصلحة\nAgile وتخطيط السبرنت\nاستراتيجية الذهاب للسوق",
				'outcomes'   => "Lead product discovery and research\nBuild and prioritize roadmaps\nDefine and track product metrics\nCommunicate with stakeholders effectively",
				'outcomes_ar' => "قيادة اكتشاف المنتج والبحث\nبناء وأولوية خرائط الطريق\nتحديد وتتبع مقاييس المنتج\nالتواصل الفعال مع أصحاب المصلحة",
				'skills'     => "Product Strategy, User Research, Roadmapping, Agile, Analytics",
				'skills_ar'  => "استراتيجية المنتج, بحث المستخدم, خارطة الطريق, Agile, التحليلات",
			),
		);

		$limit = min( $count, count( $courses_data ) );

		for ( $i = 0; $i < $limit; $i++ ) {
			$item = $courses_data[$i];
			$existing = get_page_by_title( $item['title'], OBJECT, 'course' );
			if ( ! $existing ) {
				$post_id = wp_insert_post( array(
					'post_title'   => $item['title'],
					'post_content' => $item['content'],
					'post_status'  => 'publish',
					'post_type'    => 'course',
				) );

				update_post_meta( $post_id, '_title_ar', $item['title_ar'] );
				update_post_meta( $post_id, '_content_ar', $item['content_ar'] );
				update_post_meta( $post_id, '_course_price', $item['price'] );
				update_post_meta( $post_id, '_course_price_orig', $item['orig'] );
				update_post_meta( $post_id, '_course_badge', $item['badge'] );
				update_post_meta( $post_id, '_course_duration', $item['duration'] );
				update_post_meta( $post_id, '_course_lessons_count', $item['lessons'] );
				update_post_meta( $post_id, '_course_rating', $item['rating'] );
				update_post_meta( $post_id, '_course_reviews_count', $item['reviews'] );
				update_post_meta( $post_id, '_course_syllabus', $item['syllabus'] );
				update_post_meta( $post_id, '_course_syllabus_ar', $item['syllabus_ar'] );
				update_post_meta( $post_id, '_course_outcomes', $item['outcomes'] );
				update_post_meta( $post_id, '_course_outcomes_ar', $item['outcomes_ar'] );
				update_post_meta( $post_id, '_course_skills', $item['skills'] );
				update_post_meta( $post_id, '_course_skills_ar', $item['skills_ar'] );

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
				'title'      => 'React 19 Complete Breakdown: Server Components & Actions',
				'title_ar'   => 'تحليل شامل لـ React 19: المكونات الخادمية و Actions',
				'content'    => "<p>React 19 introduces Server Components as a first-class primitive. In this article, we break down every new feature with live code examples.</p><p>Server Components render on the server and stream HTML to the client, reducing bundle size and improving time-to-interactive. Actions replace manual form handling with a declarative API.</p><p>We also cover the new React Compiler, which eliminates the need for useMemo and useCallback in most cases.</p>",
				'content_ar' => "<p>يقدم React 19 المكونات الخادمية كعنصر أساسي. في هذا المقال، نحلل كل ميزة جديدة بأمثلة برمجية حية.</p><p>تُعرض المكونات الخادمية على الخادم وتبث HTML للعميل، مما يقلل حجم الحزمة ويحسن وقت التفاعل. تحل Actions محل معالجة النماذج اليدوية بواجهة تعريفية.</p><p>نغطي أيضاً مترجم React الجديد، الذي يلغي الحاجة إلى useMemo و useCallback في معظم الحالات.</p>",
			),
			array(
				'title'      => 'How to Build Scalable Figma Design Systems',
				'title_ar'   => 'كيف تبني أنظمة تصميم قابلة للتوسع في Figma',
				'content'    => "<p>From variables and tokens to developer handoff — here is the complete workflow for building a design system that scales with your team.</p><p>Start with a solid token foundation: colors, typography, spacing, and elevation. Then build components using auto layout and variants.</p><p>Finally, establish a handoff process that keeps designers and developers in sync.</p>",
				'content_ar' => "<p>من المتغيرات والرموز إلى التسليم للمطور — إليك سير العمل الكامل لبناء نظام تصميم يتوسع مع فريقك.</p><p>ابدأ بأساس رموز قوي: الألوان والطباعة والتباعد والارتفاع. ثم ابني المكونات باستخدام الطبقة التلقائية والمتغيرات.</p><p>أخيراً، أنشئ عملية تسليم تبقي المصممين والمطورين متزامنين.</p>",
			),
			array(
				'title'      => 'Pandas vs Polars in 2026: Which Performs Better?',
				'title_ar'   => 'Pandas مقابل Polars في 2026: أيهما أفضل أداءً؟',
				'content'    => "<p>We benchmark Pandas and Polars on large-scale datasets (10M+ rows) measuring speed, memory usage, and API ergonomics.</p><p>Polars leverages Rust and Apache Arrow for zero-copy operations, while Pandas remains the ecosystem standard with broader community support.</p><p>Our verdict: use Polars for performance-critical pipelines, Pandas for exploratory analysis.</p>",
				'content_ar' => "<p>نقارن Pandas و Polars على مجموعات بيانات ضخمة (أكثر من 10 ملايين صف) لقياس السرعة واستهلاك الذاكرة وسهولة الاستخدام.</p><p>يستفيد Polars من Rust و Apache Arrow لعمليات النسخ الصفري، بينما يبقى Pandas معيار النظام البيئي بدعم مجتمعي أوسع.</p><p>حكمنا: استخدم Polars لخطوط الأداء الحرجة، و Pandas للتحليل الاستكشافي.</p>",
			),
		);

		$limit = min( $count, count( $posts ) );

		for ( $i = 0; $i < $limit; $i++ ) {
			$item = $posts[$i];
			$existing = get_page_by_title( $item['title'], OBJECT, 'post' );
			if ( ! $existing ) {
				$post_id = wp_insert_post( array(
					'post_title'   => $item['title'],
					'post_content' => $item['content'],
					'post_status'  => 'publish',
					'post_type'    => 'post',
				) );
				update_post_meta( $post_id, '_title_ar', $item['title_ar'] );
				update_post_meta( $post_id, '_content_ar', $item['content_ar'] );
			}
		}
	}

	/**
	 * Clear all seeded items
	 */
	public static function clear_all() {
		$post_types = array( 'course', 'instructor', 'learning_path', 'faq', 'testimonial', 'team', 'post', 'page' );
		$protect    = array_filter( array(
			(int) get_option( 'page_on_front' ),
			(int) get_option( 'page_for_posts' ),
		) );

		foreach ( $post_types as $pt ) {
			$posts = get_posts( array( 'post_type' => $pt, 'posts_per_page' => -1, 'post_status' => 'any' ) );
			foreach ( $posts as $p ) {
				if ( in_array( (int) $p->ID, $protect, true ) ) {
					continue;
				}
				wp_delete_post( $p->ID, true );
			}
		}

		// Reset reading settings to defaults.
		delete_option( 'page_on_front' );
		delete_option( 'page_for_posts' );
		update_option( 'show_on_front', 'posts' );
	}

	/**
	 * Seed Learning Paths
	 */
	public static function seed_learning_paths() {
		$paths = array(
			array(
				'title'      => 'Frontend Foundations (HTML, CSS, React)',
				'title_ar'   => 'أساسيات الواجهات (HTML و CSS و React)',
				'content'    => 'A curated multi-course career track to get you job-ready for frontend roles. Start from zero and build a portfolio of 5 real projects.',
				'content_ar' => 'مسار مهني متعدد الدورات ليجهزك لشغل الواجهات. ابدأ من الصفر وابنِ معرضاً من 5 مشاريع حقيقية.',
				'weeks'      => '4',
				'courses'    => '3',
				'badge'      => 'New',
			),
			array(
				'title'      => 'Backend Architecture (Node.js & APIs)',
				'title_ar'   => 'هندسة الواجهات الخلفية (Node.js و APIs)',
				'content'    => 'Master server-side development with Node.js. Learn REST and GraphQL API design, authentication, databases, and microservices.',
				'content_ar' => 'أتقن التطوير من جانب الخادم بـ Node.js. تعلم تصميم واجهات REST و GraphQL والمصادقة وقواعد البيانات والخدمات المصغرة.',
				'weeks'      => '6',
				'courses'    => '4',
				'badge'      => 'Bestseller',
			),
			array(
				'title'      => 'Databases, DevOps & Deployment',
				'title_ar'   => 'قواعد البيانات و DevOps والنشر',
				'content'    => 'Learn database modeling, CI/CD pipelines, containerization with Docker, and cloud deployment. Everything you need to ship apps to production.',
				'content_ar' => 'تعلم نمذجة قواعد البيانات وخطوط CI/CD والحاويات بـ Docker والنشر السحابي. كل ما تحتاجه لنشر التطبيقات على بيئة الإنتاج.',
				'weeks'      => '4',
				'courses'    => '3',
				'badge'      => 'Free',
			),
		);

		foreach ( $paths as $item ) {
			$existing = get_page_by_title( $item['title'], OBJECT, 'learning_path' );
			if ( ! $existing ) {
				$post_id = wp_insert_post( array(
					'post_title'   => $item['title'],
					'post_content' => $item['content'],
					'post_status'  => 'publish',
					'post_type'    => 'learning_path',
				) );
				update_post_meta( $post_id, '_title_ar', $item['title_ar'] );
				update_post_meta( $post_id, '_content_ar', $item['content_ar'] );
				update_post_meta( $post_id, '_path_weeks', $item['weeks'] );
				update_post_meta( $post_id, '_path_courses', $item['courses'] );
				update_post_meta( $post_id, '_path_badge', $item['badge'] );
			}
		}
	}

	/**
	 * Seed FAQ Items
	 */
	public static function seed_faq() {
		$faqs = array(
			array(
				'q' => 'Do I need prior experience to start?',
				'q_ar' => 'هل أحتاج إلى خبرة سابقة للبدء؟',
				'a' => 'No. Our beginner tracks start from zero and ramp up gradually with project-based lessons.',
				'a_ar' => 'لا. مسارات المبتدئين لدينا تبدأ من الصفر وتتصاعد تدريجياً بدروس قائمة على المشاريع.',
			),
			array(
				'q' => 'Are the courses bilingual?',
				'q_ar' => 'هل الدورات ثنائية اللغة؟',
				'a' => 'Yes. Every course offers Arabic and English captions, and instructors provide voice intros in both languages.',
				'a_ar' => 'نعم. كل دورة توفر ترجمات عربية وإنجليزية، ويقدم المدربون مقدمة صوتية بكلتا اللغتين.',
			),
			array(
				'q' => 'Is there a money-back guarantee?',
				'q_ar' => 'هل هناك ضمان استرداد المال؟',
				'a' => 'Yes — a 30-day money-back guarantee applies to all paid courses, no questions asked.',
				'a_ar' => 'نعم — يوجد ضمان استرداد لمدة 30 يوماً ينطبق على جميع الدورات المدفوعة، دون أي أسئلة.',
			),
			array(
				'q' => 'Do I get a certificate?',
				'q_ar' => 'هل أحصل على شهادة؟',
				'a' => 'Upon completing a course you receive a verifiable certificate you can share on LinkedIn.',
				'a_ar' => 'عند إكمال دورة تحصل على شهادة موثقة يمكنك مشاركتها على LinkedIn.',
			),
			array(
				'q' => 'Can I download the lesson resources?',
				'q_ar' => 'هل يمكنني تنزيل موارد الدروس؟',
				'a' => 'Yes. Source code, design files, and slides are downloadable for every enrolled course.',
				'a_ar' => 'نعم. الكود المصدري وملفات التصميم والشرائح قابلة للتنزيل لكل دورة مسجلة.',
			),
		);

		$order = 0;
		foreach ( $faqs as $item ) {
			$existing = get_page_by_title( $item['q'], OBJECT, 'faq' );
			if ( ! $existing ) {
				$post_id = wp_insert_post( array(
					'post_title'    => $item['q'],
					'post_content'  => $item['a'],
					'post_status'   => 'publish',
					'post_type'     => 'faq',
					'menu_order'    => $order,
				) );
				update_post_meta( $post_id, '_title_ar', $item['q_ar'] );
				update_post_meta( $post_id, '_content_ar', $item['a_ar'] );
			}
			$order++;
		}
	}

	/**
	 * Seed Testimonials
	 */
	public static function seed_testimonials() {
		$items = array(
			array(
				'name'      => 'Ahmed Al-Sayed',
				'name_ar'   => 'أحمد السيد',
				'role'      => 'Frontend Developer @ Acme',
				'role_ar'   => 'مطور واجهات في Acme',
				'rating'    => '5',
				'img'       => 'assets/img/testimonials/ahmed.jpg',
				'quote'     => 'I went from zero to landing a React role in 4 months. The project-based approach made all the difference.',
				'quote_ar'  => 'انتقلت من الصفر إلى الحصول على وظيفة React في 4 أشهر. النهج القائم على المشاريع صنع الفرق كله.',
			),
			array(
				'name'      => 'Fatima Noor',
				'name_ar'   => 'فاطمة نور',
				'role'      => 'UI/UX Designer @ Nimbus',
				'role_ar'   => 'مصممة واجهات في Nimbus',
				'rating'    => '5',
				'img'       => 'assets/img/testimonials/fatima.jpg',
				'quote'     => 'The Figma systems course paid for itself ten times over. My handoff process is night and day now.',
				'quote_ar'  => 'دورة أنظمة Figma أثمرت عشرة أضعاف. عملية التسليم لدي أصبحت مختلفة تماماً.',
			),
		);

		foreach ( $items as $item ) {
			$existing = get_page_by_title( $item['name'], OBJECT, 'testimonial' );
			if ( ! $existing ) {
				$post_id = wp_insert_post( array(
					'post_title'   => $item['name'],
					'post_content' => $item['quote'],
					'post_status'  => 'publish',
					'post_type'    => 'testimonial',
				) );
				update_post_meta( $post_id, '_title_ar', $item['name_ar'] );
				update_post_meta( $post_id, '_content_ar', $item['quote_ar'] );
				update_post_meta( $post_id, '_testimonial_role', $item['role'] );
				update_post_meta( $post_id, '_testimonial_role_ar', $item['role_ar'] );
				update_post_meta( $post_id, '_testimonial_rating', $item['rating'] );
				update_post_meta( $post_id, '_thumbnail_url', $item['img'] );
			}
		}
	}

	/**
	 * Seed Team Members
	 */
	public static function seed_team() {
		$members = array(
			array(
				'name'      => 'Khaled Reda',
				'name_ar'   => 'خالد رضا',
				'role'      => 'Founder & CEO',
				'role_ar'   => 'المؤسس والرئيس التنفيذي',
				'content'   => 'Leads the platform vision and curriculum strategy. 15+ years in tech education.',
				'content_ar' => 'يقود رؤية المنصة واستراتيجية المنهج. أكثر من 15 عاماً في التعليم التقني.',
				'img'       => 'assets/img/team/khaled.jpg',
				'social'    => 'https://linkedin.com/in/',
			),
			array(
				'name'      => 'Mona Adel',
				'name_ar'   => 'منى عادل',
				'role'      => 'Head of Curriculum',
				'role_ar'   => 'رئيسة المنهج',
				'content'   => 'Oversees course quality and instructor partnerships. Former university professor.',
				'content_ar' => 'تشرف على جودة الدورات وشراكات المدربين. أستاذة جامعية سابقة.',
				'img'       => 'assets/img/team/mona.jpg',
				'social'    => 'https://linkedin.com/in/',
			),
		);

		foreach ( $members as $item ) {
			$existing = get_page_by_title( $item['name'], OBJECT, 'team' );
			if ( ! $existing ) {
				$post_id = wp_insert_post( array(
					'post_title'   => $item['name'],
					'post_content' => $item['content'],
					'post_status'  => 'publish',
					'post_type'    => 'team',
				) );
				update_post_meta( $post_id, '_title_ar', $item['name_ar'] );
				update_post_meta( $post_id, '_content_ar', $item['content_ar'] );
				update_post_meta( $post_id, '_team_role', $item['role'] );
				update_post_meta( $post_id, '_team_role_ar', $item['role_ar'] );
				update_post_meta( $post_id, '_team_social', $item['social'] );
				update_post_meta( $post_id, '_thumbnail_url', $item['img'] );
			}
		}
	}
}
