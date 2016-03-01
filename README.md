# damn-plugins
Damn website shared plugins

Required 3rd party, non-repo plugins for DAMN:

*	[Advanced Custom Fields Pro](http://www.advancedcustomfields.com/pro/)
*	[s2Member Pro](http://s2member.com/)	
*	[Search Filter Pro](http://www.designsandcode.com/wordpress-plugins/search-filter-pro/)


### Deployment
The plugin folder should only consist of stable, 3rd party plugins.
Therefore, deployment can be safely executed as simple update (no releases- or atomic structure).

### Symlinks
Don't forget to symlink the plugins after release folder creation.

```
ln -s $SHARED/web/app/plugins/advanced-custom-fields-pro $RELEASE/web/app/plugins/advanced-custom-fields-pro
ln -s $SHARED/web/app/plugins/s2member-pro $RELEASE/web/app/plugins/s2member-pro
ln -s $SHARED/web/app/plugins/search-filter-pro $RELEASE/web/app/plugins/search-filter-pro
```