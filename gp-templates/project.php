<?php
gp_title( sprintf( __('%s &lt; GlotPress'), esc_html( $project->name ) ) );
gp_breadcrumb_project( $project );
wp_enqueue_script( 'common' );
$edit_link = gp_link_project_edit_get( $project, '(edit)', array( 'before' => '<span class="edit">', 'after' => '</span>' ) );
$parity = gp_parity_factory();
gp_tmpl_header();
?>
<h2><?php echo esc_html( $project->name ); ?> <?php echo $edit_link; ?></h2>
<p class="description">
	<?php echo $project->description; ?>
</p>

<?php if ( $can_write ): ?>
<div class="actionlist">
	<a href="#" class="project-actions" id="project-actions-toggle"><?php _e('Project actions &darr;'); ?></a>
	<div class="project-actions">
		<ul>
			<li><?php gp_link( gp_url_project( $project, 'import-originals' ), __( 'Import originals' ) ); ?></li>
			<li><?php gp_link( gp_url_project( $project, array( '-permissions' ) ), __('Permissions') ); ?></li>
			<li><?php gp_link( gp_url_project( '', '-new', array('parent_project_id' => $project->id) ), __('New Sub-Project') ); ?></li>
			<li><?php gp_link( gp_url( '/sets/-new', array( 'project_id' => $project->id ) ), __('New Translation Set') ); ?></li>
			<li><?php gp_link( gp_url_project( $project, array( '-mass-create-sets' ) ), __('Mass-create Translation Sets') ); ?></li>
		</ul>
	</div>
</div>
<?php endif; ?>

<?php if ($sub_projects): ?>
<div id="sub-projects">
<h3><?php _e('Sub-projects'); ?></h3>
<dl>
<?php foreach($sub_projects as $sub_project): ?>
	<dt>
		<?php gp_link_project( $sub_project, esc_html( $sub_project->name ) ); ?>
		<?php gp_link_project_edit( $sub_project ); ?>
	</dt>
	<dd>
		<?php echo esc_html( gp_html_excerpt( $sub_project->description, 100 ) ); ?>
	</dd>
<?php endforeach; ?>
</dl>
</div>
<?php endif; ?>

<?php if ( $translation_sets ): ?>
<div id="translation-sets">
	<h3>Translations</h3>
<?php /*
	<ul class="translation-sets">
	<?php foreach( $translation_sets as $set ): ?>    
		<li>
			<?php gp_link( gp_url_project( $project, gp_url_join( $set->locale, $set->slug ) ), $set->name_with_locale() ); ?>
			<?php gp_link_set_edit( $set, $project ); ?>
			<span class="stats secondary">
				<!--
				<span class="translated" title="translated"><?php echo $set->current_count(); ?></span>
				<span class="untranslated" title="untranslated"><?php echo $set->untranslated_count(); ?></span>
				-->
			<?php if ( GP::$user->can( 'approve', 'translation-set', $set->id ) && $waiting = $set->waiting_count() ): ?>
				<?php gp_link( gp_url_project( $project, gp_url_join( $set->locale, $set->slug ),
						array('filters[translated]' => 'yes', 'filters[status]' => 'waiting') ), $waiting, array('class' => 'waiting', 'title' => 'waiting') ); ?>
			<?php endif; ?>
			<?php if ( GP::$user->can( 'approve', 'translation-set', $set->id ) && $warnings = $set->warnings_count() ): ?>
				<?php gp_link( gp_url_project( $project, gp_url_join( $set->locale, $set->slug ),
						array('filters[translated]' => 'yes', 'filters[warnings]' => 'yes' ) ), $warnings, array('class' => 'warnings', 'title' => 'with warnings') ); ?>
			<?php endif; ?>
			
			<?php do_action( 'project_template_translation_set_extra', $set, $project ); ?>
			</span>
		</li>
	<?php endforeach; ?>
	</ul>
<?php */ ?>
	<table class="translation-sets">
		<thead>
			<tr>
				<th><?php _e( 'Language' ); ?></th>
				<th><?php echo _x( '%', 'language translation percent header' ); ?></th>
				<th><?php _e( 'Translated' ); ?></th>
				<th><?php _e( 'Untranslated' ); ?></th>
				<th><?php _e( 'Waiting' ); ?></th>
				<th><?php _e( 'Extra' ); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach( $translation_sets as $set ): ?>
			<tr class="<?php echo $parity(); ?>">
				<td>
					<strong><?php gp_link( gp_url_project( $project, gp_url_join( $set->locale, $set->slug ) ), $set->name_with_locale() ); ?></strong>
				</td>
				<td class="stats percent"><?php echo $set->percent_translated; ?></td>
				<td class="stats translated" title="translated"><?php gp_link( gp_url_project( $project, gp_url_join( $set->locale, $set->slug ),
							array('filters[translated]' => 'yes', 'filters[status]' => 'current') ), $set->current_count );; ?></td>
				<td class="stats untranslated" title="untranslated"><?php gp_link( gp_url_project( $project, gp_url_join( $set->locale, $set->slug ),
							array('filters[translated]' => 'no', 'filters[status]' => 'either') ), $set->untranslated_count ); ?></td>
				<td class="stats waiting"><?php gp_link( gp_url_project( $project, gp_url_join( $set->locale, $set->slug ),
							array('filters[translated]' => 'yes', 'filters[status]' => 'waiting') ), $set->waiting_count ); ?></td>
				<td>
					<?php do_action( 'project_template_translation_set_extra', $set, $project ); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php elseif ( !$sub_projects ): ?>
	<p><?php _e('There are no translations of this project.'); ?></p>
<?php endif; ?>
<div class="clear"></div>


<script type="text/javascript" charset="utf-8">
	$gp.showhide('a.personal-options', 'Personal project options &darr;', 'Personal project options &uarr;', 'div.personal-options', '#source-url-template');
	$('div.personal-options').hide();
	$gp.showhide('a.project-actions', 'Project Actions &darr;', 'Project Actions &uarr;', 'div.project-actions', null);
	$('div.project-actions').hide();
	
</script>
<?php gp_tmpl_footer();
