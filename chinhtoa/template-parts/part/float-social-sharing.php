<?php 
global $wp;
$shareURL = home_url( $wp->request );
$enc      = rawurlencode( $shareURL );
$fbURL    = 'https://www.facebook.com/sharer/sharer.php?u=' . $enc;
$twURL    = 'https://twitter.com/intent/tweet?url=' . $enc;
// "Share via email" opens the user's mail client with the URL prefilled — no fixed recipient.
$email    = 'mailto:?subject=&body=' . $enc;
?>

<div id="ct-float-social-share">
  <a class="social-item social-fb" href="<?php echo esc_url($fbURL); ?>" target="_blank" rel="noopener">
    <svg class="ct-icon ct-icon-facebook" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" style="vertical-align:-0.125em"><path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951"/></svg>
  </a>
  <a class="social-item social-tw" href="<?php echo esc_url($twURL); ?>" target="_blank" rel="noopener">
    <svg class="ct-icon ct-icon-twitter" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" style="vertical-align:-0.125em"><path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334q.002-.211-.006-.422A6.7 6.7 0 0 0 16 3.542a6.7 6.7 0 0 1-1.889.518 3.3 3.3 0 0 0 1.447-1.817 6.5 6.5 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.32 9.32 0 0 1-6.767-3.429 3.29 3.29 0 0 0 1.018 4.382A3.3 3.3 0 0 1 .64 6.575v.045a3.29 3.29 0 0 0 2.632 3.218 3.2 3.2 0 0 1-.865.115 3 3 0 0 1-.616-.057 3.28 3.28 0 0 0 3.067 2.277A6.6 6.6 0 0 1 .78 13.58a6 6 0 0 1-.78-.045A9.34 9.34 0 0 0 5.026 15"/></svg>
  </a>
  <a class="social-item social-email" href="<?php echo esc_url($email); ?>" target="_blank" rel="noopener">
    <svg class="ct-icon ct-icon-envelope" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" style="vertical-align:-0.125em"><path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/></svg>
  </a>
  <a id="mass-times-btn" class="social-item mass-times-btn" href="#mass-times-widget">
    <?php esc_html_e('Giờ Thánh Lễ', 'chinhtoa'); ?>
  </a>
</div>