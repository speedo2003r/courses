## Goal
A stable, secure, fully-dynamic WordPress theme that faithfully reproduces the HTML frontend at `/mnt/e/freelancer/courses`, with all business content admin-editable. Reuse the existing architecture (CPTs, meta, customizer, seeder, form handlers) — fix and wire it rather than rebuild.

Decisions confirmed: (1) free enrollment flow, no fake payment gateway, architecture stays gateway-ready; (2) new CPTs: `faq`, `testimonial`, `team` (+ existing `course`/`instructor`/`learning_path`); (3) keep PHP `?lang=` bilingual, drop `i18n.js`.

All changes honor `DO_NOTS.md`: logical CSS props only, no fake urgency, no hardcoded pixel widths, escape all output.

---

## Phase 1 — Routing & template-hierarchy fixes (critical, unblocks the rest)

**`functions.php` → `edtech_register_custom_post_types()`:**
- Course: `has_archive => 'catalog'` (so `/catalog/` is the real CPT archive). Rewrite slug stays `course`.
- Learning path: `has_archive => 'learning-paths'`.
- Instructor: keep `has_archive => 'instructors'`.

**`inc/seeder.php` → `seed_pages()`:**
- Drop the redundant `catalog` and `home` page entries (CPT archive + `front-page.php` cover them).
- After creating pages, set `update_option('show_on_front','page')`, `update_option('page_on_front', <home page id>)`, `update_option('page_for_posts', <blog page id>)`.
- Remove `page-blog.php` shim file entirely; remove the `Template Name: Blog Page` header from `home.php` (keep it as the reserved posts-index template).

**`archive.php`:** branch by post type — `course` archive → `content-course-card`; everything else (blog/category/tag) → new `template-parts/content-post-card.php` (excerpt + thumbnail + permalink).

**`single.php`:** add CPT branch at top — `instructor` → `single-instructor.php`, `learning_path` → `single-learning_path.php`, else fall through to current blog layout.

**`archive-instructor.php`:** rewrite to use the main loop (`have_posts()`/`the_post()`) and `get_template_part('template-parts/content-instructor-card')`; delete the hardcoded 4-item fallback and `get_posts` shortcut.

**Replace fragile `home_url('/catalog')` links** (in `single.php`, `page-learning-paths.php`, `page-student-dashboard.php`, `front-page.php`) with `get_post_type_archive_link('course')`. Replace `home_url('/course-detail')` (front-page sandbox) with the catalog link.

---

## Phase 2 — New CPTs, taxonomies, meta

**`functions.php`:** register `faq`, `testimonial`, `team` CPTs (`show_in_rest=>true`, appropriate `menu_icon`, supports). FAQ: title+editor+menu_order+page-attributes. Testimonial: title+editor+thumbnail + meta (role, rating). Team: title+editor+thumbnail + meta (role).

**`inc/content-model.php`:** add `register_post_meta` for testimonial role/rating and team role; add meta-box render+save functions (mirroring the existing course metabox pattern with nonces + capability checks).

**`inc/seeder.php`:** add `seed_faq()`, `seed_testimonials()`, `seed_team()` with a few realistic bilingual rows each; call from `run()`; include in `clear_all()` post_types.

---

## Phase 3 — Wire the (currently dead) customizer into templates

**`inc/customizer.php`:** extend the fields list with a few more (hero stat students count, completion rate, free-masterclass CTA label, about/faq page intros already come from page content). Keep it lean.

**`front-page.php` hero:** replace hardcoded title/description/CTA with `edtech_get_site_setting('hero_*')`.

**`footer.php`:** use `edtech_get_site_setting('footer_text')`, render social links (`social_linkedin`, `social_youtube`) when set, contact email/phone when set.

**`header.php`:** use `the_custom_logo()` (support already registered) with text fallback; switch nav to `wp_nav_menu('primary')` with a `fallback_cb` that prints the current page list so it works before an admin creates a menu. Same for footer menus.

---

## Phase 4 — Dynamicize `front-page.php`
- **Instructor grid:** `new WP_Query(['post_type'=>'instructor','posts_per_page'=>4])`, pass each post to `content-instructor-card` (refactored — see Phase 5).
- **Stats:** `Premium Courses` and `Expert Instructors` → `wp_count_posts('course')` / `wp_count_posts('instructor')` (published). `Active Students` and `Completion Rate` → customizer settings (marketing claims, admin-editable).
- **Skill tree:** `WP_Query('learning_path')` rendering one node per path (title, meta: weeks/courses count, badge). HTML reference shows 3 phases — the query replaces the single hardcoded node.
- **Category chips:** loop `get_terms('course_category')` and link to `get_term_link($term)`.

---

## Phase 5 — Reusable card refactor
**`template-parts/content-instructor-card.php`:** make it work in the loop (read `get_the_ID()`, `get_post_meta`, `get_the_post_thumbnail_url`, `_instructor_title`, `_instructor_audio_url`, rating/students meta) AND accept `$args` override for the front-page static-image case. Prefix `data-audio` with `get_template_directory_uri()` only when the stored value is a relative path (already done — verify archive path too).

**`template-parts/content-course-card.php`:** Enroll link → `edtech_get_checkout_url(get_the_ID())`; instructor avatar/name from the course's `_course_instructor` meta (fallback to first instructor CPT); keep all existing meta rendering.

---

## Phase 6 — Dynamicize page templates (content from WP data, not hardcoded)

- **`single-course.php`:** description → `the_content()`; add course meta `_course_syllabus`, `_course_outcomes`, `_course_skills` (HTML textareas in the course metabox), render with `wp_kses_post`. Breadcrumb uses `get_post_type_archive_link('course')`. Enroll → `edtech_get_checkout_url()`.
- **`archive-course.php`:** delete the broken 6-card fallback loop (it rendered cards with no post context) → show a "no courses yet" empty state. Render sidebar category checkboxes and chip bar from `get_terms()` so `data-category` matches taxonomy slugs/names exactly (aligns with `filter.js`).
- **`page-faq.php`:** `WP_Query('faq')` accordion; each item = title (question) + content (answer).
- **`page-learning-paths.php`:** `WP_Query('learning_path')` skill-tree nodes.
- **`page-about.php`:** main copy from `the_content()`; team section → `WP_Query('team')` (or `instructor` fallback); pillars as page content.
- **`page-checkout.php`:** read `course_id` via `edtech_get_course_id_from_request()`; render order summary (title, price, price_orig) from course meta; form posts to `admin-post.php` action `edtech_enroll` (existing handler) with nonce + `course_id`. Auth tabs (login/register) already handled in `functions.php` init — point forms at the same handlers. No payment fields (gateway-ready stub documented).
- **`page-certificates.php`:** list courses from `edtech_get_enrolled_course_ids()` where progress meta == 100; render a certificate card per completed course (no separate certificate CPT, per decision).
- **`page-student-dashboard.php`:** enrolled courses → `edtech_get_enrolled_course_ids()` + `get_posts(['post_type'=>'course','post__in'=>…])`; progress bars from `_edtech_course_progress_<id>` user meta; remove inline POST handling, point auth form at the `functions.php` login/register handlers.
- **`page-student-settings.php`:** prefill `wp_get_current_user()` (display_name, email) + user meta (headline, portfolio); form action → `admin-post.php` action `edtech_profile_update` (handler exists in `forms.php`) with nonce.
- **`page-instructor-dashboard.php`:** stats from `wp_count_posts`/queries filtered by `post_author = current user` (instructor) or courses where `_course_instructor` matches; list their courses.
- **`page-course-builder.php`:** form action → `admin-post.php` action `edtech_course_builder` (handler exists) with nonce; keep field names the handler expects (`course_title`, `course_description`, `course_price`).
- **`page-lesson-workspace.php`:** read `course_id`/`lesson` from request; render curriculum from course syllabus meta; "Mark complete" button → `admin-post.php` action that increments `_edtech_course_progress_<id>` (new small handler in `forms.php`, nonce + logged-in + enrolled check).
- **`single.php` (blog):** tags via `get_the_tags()`; read-time computed from `post_content` str_word_count; related posts via `get_posts` same category. Fix `/blog/` links to `get_permalink(get_option('page_for_posts'))`.
- **`page-free-masterclass.php`:** copy from `the_content()` + customizer CTA; keep poster `<img>` (design uses posters, no real media file).

---

## Phase 7 — JS fixes
- **`search.js`:** remove the hardcoded `COURSES` array; instead `wp_localize_script('edtech-search','EDTECH_SEARCH', <course data>)` from `functions.php` (titles + permalinks + first category slug). Suggestions now link to real permalinks.
- **`app.js`:** fix active-nav highlighting — compare each `.nav-link` href pathname to `location.pathname` (not to a filename), and support `data-nav` matching. Keep toast/mobile-menu logic.
- **`filter.js`:** no logic change; values now align because chips/cards are rendered from taxonomy slugs in Phase 6.
- **`i18n.js`:** delete the file (not enqueued by `functions.php`, but remove to avoid confusion); language switching stays via `?lang=` links.
- **`audio.js`/`player.js`:** no change needed once `data-audio` paths are correct and posters are intentional.

---

## Phase 8 — Security & correctness sweep
- **`functions.php` line 408:** fix `current_user_can('edit_post', )` → `current_user_can('edit_post', $post_id)` (broken capability check).
- Verify all dynamic output uses `esc_html`/`esc_url`/`wp_kses_post`; add where missing (inline style values from meta).
- Confirm every front-end form has nonce + capability/login check (most already do via `forms.php`).
- `edtech_catalog_query`: GET filters are fine without nonces; ensure `s`/taxonomy inputs sanitized (already are).

---

## Phase 9 — Cleanup
- Remove `page-blog.php` shim (Phase 1).
- Remove dead `i18n.js` (Phase 7).
- Strip the unused `home_url('/catalog')`/`/course-detail` references after replacements.
- Ensure `functions.php` enqueue list doesn't reference removed files.

---

## Phase 10 — Verification
- Bring up Docker stack (`docker compose up -d`), run the seeder from admin, visit: home, catalog, single course, instructors, instructor single, learning paths, blog, single post, about, faq, free-masterclass, checkout, student-dashboard, student-settings, lesson-workspace, certificates, instructor-dashboard, course-builder, 404.
- Check: no PHP fatals, no console errors, nav active states, search suggestions link to real permalinks, catalog filter works, enroll flow enrolls, profile save works, RTL toggle works, responsive at mobile/tablet/desktop (compare side-by-side with HTML reference).
- Browser-based visual check on port 8090 against the HTML reference for the key pages (home, catalog, course detail).

---

## Files significantly changed
- `functions.php`, `inc/content-model.php`, `inc/customizer.php`, `inc/seeder.php`, `inc/forms.php`
- `header.php`, `footer.php`, `front-page.php`, `home.php`, `archive.php`, `archive-course.php`, `archive-instructor.php`, `single.php`, `single-course.php`
- New: `single-instructor.php`, `single-learning_path.php`, `template-parts/content-post-card.php`
- Edited: `template-parts/content-course-card.php`, `template-parts/content-instructor-card.php`
- Edited page templates: about, faq, learning-paths, free-masterclass, checkout, certificates, student-dashboard, student-settings, instructor-dashboard, course-builder, lesson-workspace
- JS: `assets/js/search.js`, `assets/js/app.js`; removed `assets/js/i18n.js`; removed `page-blog.php`

## Known limitations (to report at end)
- No real payment processing (by design — gateway-ready stub).
- Video "player" remains poster-image based (no real media assets in the project; matches HTML reference behavior).
- Bilingual is runtime `?lang=`/cookie based (not URL-prefixed); the `ar/` HTML folder is the design reference only.
- Audio intro files (`assets/media/audio/*.mp3`) are referenced but not present in the repo — buttons will attempt to load and fail gracefully (matches current behavior).