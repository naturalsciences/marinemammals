var SymfonyPrototype = function ($collectionHolder, $addTagLink,$prototypeName) {
    var collectionHolder = $collectionHolder || $('ul.entity');
    var addTagLink = $addTagLink || $('<a href="#" class="add_entity">Add an entity</a>');
    var newLinkLi = $('<li class="form-inline"></li>').append(addTagLink);
    var prototypeName = $prototypeName || '__name__';

    this.addAddLink = function () {

        // add a delete link to all of the existing tag form li elements
        collectionHolder.find('li').each(function () {
            addTagFormDeleteLink($(this));
        });

        collectionHolder.append(newLinkLi);

        var i=collectionHolder.find(':input').length+1;

        // count the current form inputs we have (e.g. 2), use that as the new
        // index when inserting a new item (e.g. 2)
        collectionHolder.data('index', i);

        addTagLink.on('click', function (e) {
            // prevent the link from creating a "#" on the URL
            e.preventDefault();

            // add a new tag form (see next code block)
            addTagForm(collectionHolder, newLinkLi);
        });
    };

    var addTagForm = function ($collectionHolder, $newLinkLi) {
        // Get the data-prototype explained earlier
        var prototype = $collectionHolder.data('prototype');

        // get the new index
        var index = $collectionHolder.data('index');

        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have

        var re = new RegExp(prototypeName,'g');
        var newForm = prototype.replace(re, index);

        // increase the index with one for the next item
        $collectionHolder.data('index', index + 1);

        // Display the form in the page in an li, before the "Add a tag" link li
        var $newFormLi = $('<li class="form-inline"></li>').append(newForm);
        // add a delete link to the new form
        addTagFormDeleteLink($newFormLi);
        $newLinkLi.before($newFormLi);
    };

    var addTagFormDeleteLink = function ($tagFormLi) {
        var $removeFormA = $('<a href="#">delete this tag</a>');
        $tagFormLi.append($removeFormA);

        $removeFormA.on('click', function (e) {
            // prevent the link from creating a "#" on the URL
            e.preventDefault();

            // remove the li for the tag form
            $tagFormLi.remove();
        });
    };
};
