<x-app-layout>
    <x-slot name="slim"></x-slot>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-color_text leading-tight px-4">
                Updates
            </h2>
        </div>
    </x-slot>
    <x-slot name="title">
        Updates
    </x-slot>

    <div class="text-color_text space-y-5">
        <div class="container mx-auto px-4">
            <section class="mb-6">
                <h2 class="text-base font-semibold mb-2">Test Version 0.3.11</h2>
                <div class="text-xs space-y-1">
                    <div>Localization of pages has been improved. Now the button works on every page and also memorizes
                        settings for each user.</div>
                    <div>Implemented dynamic category display on the homepage, showing up to 3 active posts per
                        category.</div>
                    <div>The level up system has been changed, as well as all levels reset to 1.</div>
                    <div>Some functions and layout for publishing posts and news have been changed.</div>
                    <div>General bug fixes and performance improvements for smoother user experience.</div>
                    <div>List and quotes have been added to the editor for news and posts.</div>
                    <div>Fixes in the mobile version of the site.</div>
                    <div>Fixing links with localization</div>
                    <div>Adding an awards page</div>
                </div>
            </section>
            <section class="mb-6">
                <h2 class="text-base font-semibold mb-2">Test Version 0.3.2</h2>
                <div class="text-xs space-y-1">
                    <div>Updated game listing layout with logo, title, release date, and
                        average rating display.</div>
                    <div>Added display limit to components for badges, favorite games, and Pokémon, with empty slots to
                        maintain layout consistency.</div>
                    <div>Fixed premium purchase icon in the store to display correctly when using premium points.</div>
                    <div>Extended available nickname colors based on user items, adding red, green, and blue options.
                    </div>
                    <div>Adjusted daily login rewards system with updated reward values.</div>
                    <div>Removed Pokémon from rewards and profile sections.</div>
                    <div>Interface improvements: modified styles and reordered elements for a cleaner, more
                        user-friendly layout.</div>
                </div>
            </section>
            <section class="mb-6">
                <h2 class="text-base font-semibold mb-2">Test Version 0.2.9</h2>
                <div class="text-xs space-y-1">
                    <div>News and post titles in breadcrumbs are now abbreviated.</div>
                    <div>The name of the uploaded image for a news item is now generated by the application.</div>
                    <div>Added review checking for admins and awarding rewards for this with a notification sent to the
                        user.</div>
                    <div>The video field is now optional for news.</div>
                    <div>Fixed a bug where news that had not yet been checked or approved could be displayed.</div>
                    <div>Added some localizations.</div>
                    <div>Slightly enlarged notification window.</div>
                </div>
            </section>
            <section class="mb-6">
                <h2 class="text-base font-semibold mb-2">Test Version 0.2.2</h2>
                <div class="text-xs space-y-1">
                    <div>Fixed clipping of short article description for all languages.</div>
                    <div>Video links in the news are now parsed automatically depending on the link.</div>
                </div>
            </section>
            <section class="mb-6">
                <h2 class="text-base font-semibold mb-2">Test Version 0.2.0</h2>
                <div class="text-xs space-y-1">
                    <div>Added posts. Editing, modifying, deleting.</div>
                    <div>Added page for users to view posts in different languages and page for administrators.</div>
                    <div>Now when deleting a news item, the picture is also deleted.</div>
                    <div>Added display of posts, as well as likes and views.</div>
                    <div>Updated editor for news and posts. Added uploading and deleting images in the editor, as well
                        as templates for text tags.</div>
                    <div>Added comments and likes for posts. Added an option to reply to comments separately.</div>
                    <div>Changed font sizes and icons of some buttons.</div>
                    <div>Added a menu item for the main page for editing news and posts, for users who have rights for
                        this.</div>
                    <div>Added navigation menu in the site header for desktop and mobile versions.</div>
                    <div>Added a simple page for displaying the list of games for review on the site.</div>
                    <div>Added a video field for news, where you can specify a link to a YouTube video for the news.
                    </div>
                </div>
            </section>
            <section class="mb-6">
                <h2 class="text-base font-semibold mb-2">Test Version 0.1.8</h2>
                <div class="text-xs space-y-1">
                    <div>Corrected display of all news in regular and mobile version.</div>
                    <div>View and popularity icons have been replaced.</div>
                    <div>New item added: Violet Paint. And color Violet.</div>
                    <div>Changed language selection button.</div>
                </div>
            </section>
            <section class="mb-6">
                <h2 class="text-base font-semibold mb-2">Test Version 0.1.6</h2>
                <div class="text-xs space-y-1">
                    <div>Cookie notification added.</div>
                    <div>Added a more detailed popup with receiving an award.</div>
                    <div>Adding, editing and displaying reviews.</div>
                </div>
            </section>
            <section class="mb-6">
                <h2 class="text-base font-semibold mb-2">Test Version 0.1.1</h2>
                <div class="text-xs space-y-1">
                    <div>A start from new line button has been added to the news editor.</div>
                    <div>Added new possible tags in the news code.</div>
                    <div>Added automatic indents in news.</div>
                    <div>Added new meta tags for displaying links on third-party sites.</div>
                    <div>Corrects the display of daily rewards.</div>
                    <div>The news popularity meter now grows from all the views too.</div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
