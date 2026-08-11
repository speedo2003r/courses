<?php
/**
 * Site-wide editable settings.
 *
 * @package EdTech
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function edtech_customize_register( $customizer ) {
	$customizer->add_section( 'edtech_site_content', array(
		'title' => __( 'EdTech Site Content', 'edtech' ),
		'priority' => 30,
	) );

	$fields = array(
		'hero_eyebrow' => array( __( 'Home hero eyebrow badge', 'edtech' ), is_rtl() ? 'تقييم 4.9' : '4.9 Rating', 'text' ),
		'hero_subtitle' => array( __( 'Home hero subtitle (next to rating)', 'edtech' ), is_rtl() ? '+45,000 طالب نشط' : '45,000+ Active Students', 'text' ),
		'hero_title' => array( __( 'Home hero title (HTML allowed)', 'edtech' ), is_rtl() ? 'أتقن المهارات الرقمية الأكثر طلباً وابنِ مشاريع حقيقية' : 'Master High-Impact Digital Skills & Build Real Products', 'textarea' ),
		'hero_description' => array( __( 'Home hero description', 'edtech' ), is_rtl() ? 'توقف عن مشاهدة الشروحات السطحية. تعلّم من خبراء الصناعة، وابنِ مشاريع قوية لمحفظتك.' : 'Stop watching passive tutorials. Learn from verified industry practitioners, build portfolio-grade projects, and launch your digital career.', 'textarea' ),
		'hero_cta_label' => array( __( 'Home hero primary button label', 'edtech' ), is_rtl() ? 'اشترك الآن — 49$' : 'Enroll Now — $49', 'text' ),
		'hero_cta2_label' => array( __( 'Home hero secondary button label', 'edtech' ), is_rtl() ? 'شاهد الماستر كلاس المجاني' : 'Watch Free Masterclass', 'text' ),
		'stats_students' => array( __( 'Home stat: active students', 'edtech' ), '45000', 'text' ),
		'stats_completion' => array( __( 'Home stat: completion rate', 'edtech' ), '98%', 'text' ),
		'enroll_banner_title' => array( __( 'Home enroll banner title', 'edtech' ), is_rtl() ? 'هل أنت جاهز لبناء مستقبلك؟' : 'Ready to Build Your Future?', 'text' ),
		'enroll_banner_text' => array( __( 'Home enroll banner text', 'edtech' ), is_rtl() ? 'انضم إلى 45,000+ طالب يتعلمون بالفعل. ابدأ بالماستر كلاس المجاني — لا حاجة لبطاقة ائتمان.' : 'Join 45,000+ students already learning. Start with a free masterclass — no credit card required.', 'textarea' ),
		'contact_email' => array( __( 'Contact email', 'edtech' ), get_option( 'admin_email' ), 'email' ),
		'contact_phone' => array( __( 'Contact phone', 'edtech' ), '', 'text' ),
		'footer_text' => array( __( 'Footer description', 'edtech' ), is_rtl() ? 'نمكّن المتعلمين العرب والعالميين من المهارات الرقمية العالية التأثير عبر تعليم قائم على المشاريع.' : 'Empowering Arabic & Global learners with high-impact digital skills through expert-led project-based education.', 'textarea' ),
		'social_linkedin' => array( __( 'LinkedIn URL', 'edtech' ), '', 'url' ),
		'social_youtube' => array( __( 'YouTube URL', 'edtech' ), '', 'url' ),
		'social_twitter' => array( __( 'Twitter / X URL', 'edtech' ), '', 'url' ),
	);

	foreach ( $fields as $key => $field ) {
		$sanitize = 'sanitize_text_field';
		if ( 'email' === $field[2] ) {
			$sanitize = 'sanitize_email';
		} elseif ( 'url' === $field[2] ) {
			$sanitize = 'esc_url_raw';
		} elseif ( 'textarea' === $field[2] ) {
			$sanitize = 'sanitize_textarea_field';
		}

		$customizer->add_setting( 'edtech_' . $key, array(
			'default' => $field[1], 'sanitize_callback' => $sanitize, 'transport' => 'refresh',
		) );
		$customizer->add_control( 'edtech_' . $key, array(
			'section' => 'edtech_site_content', 'label' => $field[0],
			'type' => 'textarea' === $field[2] ? 'textarea' : ( in_array( $field[2], array( 'email', 'url' ), true ) ? $field[2] : 'text' ),
		) );
	}
}
add_action( 'customize_register', 'edtech_customize_register' );

