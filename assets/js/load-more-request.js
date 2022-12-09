/**
 * script to make http request for asynchronous posts loading
 */
// event listener btn
const requestPostBtn = document.getElementById( 'request-posts-btn' );
// response display box
const postDisplayBlock = document.getElementById( 'response-display-block' );

// setting for post blocks
const postSettings = {
    blockElem: postDisplayBlock, 
    btn_text: acadprof_rest_obj._btn_text, 
    columns_lg: acadprof_rest_obj._columns_lg, 
    columns_md: acadprof_rest_obj._columns_md, 
    columns_sm: acadprof_rest_obj._columns_sm, 
    show_thumbnail: acadprof_rest_obj._show_thumbnail, 
    show_excerpt: acadprof_rest_obj._show_excerpt
};

// rest endpoint path
let rest_ep_path = '/wp-json/wp/v2';

// allowed types
const allowed_tyes = [ 'post', 'page', 'comment', 'author', 'user', 'media' ];

// path to posts data
if ( ! allowed_tyes.includes( acadprof_rest_obj._post_type ) ) {
    rest_ep_path += `/posts`;
} else if ( acadprof_rest_obj._post_type === 'media' ) {
    rest_ep_path += `/media`;
} else {
    rest_ep_path += `/${acadprof_rest_obj._post_type}s`;
}

// adding query vars
if ( acadprof_rest_obj._posts_per_page || acadprof_rest_obj._order || acadprof_rest_obj._tag ) {
    rest_ep_path += `?`;
}

// per page 
if ( '' !== acadprof_rest_obj._posts_per_page ) {
    let parsed = parseInt( acadprof_rest_obj._posts_per_page, 10 );
    if ( typeof parsed === 'number' && parsed > 0 ) {
        rest_ep_path += `per_page=${parsed}`;
    }
}

// order
if ( acadprof_rest_obj._order.toLowerCase() === 'asc' ) {
    rest_ep_path += `&order=${acadprof_rest_obj._order.toLowerCase()}`;
}

// checks first existence of button
if ( acadprof_rest_obj._loads_by_btn && isDomElement( requestPostBtn ) ) {
    // add event handler to btn click
    requestPostBtn.addEventListener( 'click', () => {
        // instance of XMLHttpRequest
        const acadprof_http_req = new XMLHttpRequest();

        // start http request
        acadprof_http_req.open( 'GET', acadprof_rest_obj.domain_root + rest_ep_path );

        // on response load
        acadprof_http_req.onload = () => {
            if ( acadprof_http_req.status >= 200 && acadprof_http_req.status < 400 ) {
                // json to js object
                const respPostData = JSON.parse( acadprof_http_req.responseText );

                postSettings.data_arr = respPostData;
                // display posts
                showPostBlocks( postSettings );
            } else {
                console.log( 'Connected to server but no response data' );
            }
        };

        // on error
        acadprof_http_req.onerror = () => {
            console.log( 'Error in connection!' );
        };

        // sending request
        acadprof_http_req.send();
    } );
}


/**
 * Checks if passed value is DOM Element
 * @param {object} elem param for html elem 
 * @returns {boolean} true/false
 */
function isDomElement( elem ) {
    return !!( elem && elem.nodeType === 1 );
}
/**
 * Displays posts inside passed block element
 * @param {array} rData Array of response data
 * @param {*} blockElem Block element to display posts
 */
function showPostBlocks( settings = {} ) {
    // assignment by obj destructuring
    const { 
        data_arr, 
        blockElem, 
        btn_text, 
        columns_lg, 
        columns_md, 
        columns_sm, 
        show_thumbnail, 
        show_excerpt 
    }= settings;

    let htmlOut = '';

    // column class
    let col_class = 'col-12';
    const get_postfix = cols => {
        let parsed = parseInt( cols, 10 );
        if ( typeof parsed === 'number' && ( parsed > 0 && parsed <= 12 ) ) {
            return parseInt( 12 / parsed, 10 );
        } else {
            return false;
        }
    };
    
    if ( get_postfix( columns_sm ) ) {
        col_class += ` col-sm-${get_postfix( columns_sm )}`;
    }
    if ( get_postfix( columns_md ) ) {
        col_class += ` col-md-${get_postfix( columns_md )}`;
    }
    if ( get_postfix( columns_lg ) ) {
        col_class += ` col-lg-${get_postfix( columns_lg )}`;
    }

    col_class += ` my-3`;

    data_arr.forEach(postItem => {
        htmlOut += `
            <div class="${col_class}">
                <h3><a href="${postItem.link}" rel="bookmark">${postItem.title.rendered}</a></h3>
                ${postItem.excerpt.rendered}
            </div>
            `;
    });
    // bind posts html to block element
    if ( isDomElement( blockElem ) ) {
        blockElem.innerHTML = htmlOut;
    }
}