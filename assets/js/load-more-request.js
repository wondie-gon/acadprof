/**
 * script to make http request for asynchronous posts loading
 */
// event listener btn
const requestPostBtn = document.getElementById( 'request-posts-btn' );
// response display box
const postDisplayBlock = document.getElementById( 'response-display-block' );
/**
 * Object for setting post blocks
 * 
 * @param {array} data_arr Array of response data
 * @param {object} blockElem Block element to display posts
 * @param {array} exclude_ids Array containing post ids to exclude
 * @param {number} columns_lg Number of columns for large devices
 * @param {number} columns_md Number of columns for medium devices
 * @param {number} columns_sm Number of columns for small devices
 * @param {boolean} show_thumbnail Whether to display thumbnail
 * @param {string} img_size_name Thumbnail size name to display
 * @param {boolean} show_excerpt Whether to display excerpt
 */
const postSettings = {
    blockElem: postDisplayBlock, 
    btn_text: acadprof_rest_obj._btn_text, 
    exclude_ids: acadprof_rest_obj._exclude_ids, 
    columns_lg: acadprof_rest_obj._columns_lg, 
    columns_md: acadprof_rest_obj._columns_md, 
    columns_sm: acadprof_rest_obj._columns_sm, 
    show_thumbnail: acadprof_rest_obj._show_thumbnail, 
    img_size_name: acadprof_rest_obj._img_size_name, 
    show_excerpt: acadprof_rest_obj._show_excerpt
};

// rest endpoint path
let rest_ep_path = '/wp-json/wp/v2';

// allowed types
const allowed_tyes = [ 'post', 'page', 'comment', 'user', 'media' ];

// path to posts data
if ( ! allowed_tyes.includes( acadprof_rest_obj._post_type ) ) {
    rest_ep_path += `/posts`;
} else if ( acadprof_rest_obj._post_type === 'media' ) {
    rest_ep_path += `/media`;
} else {
    rest_ep_path += `/${acadprof_rest_obj._post_type}s`;
}

// adding query vars
if ( acadprof_rest_obj._show_thumbnail || acadprof_rest_obj._posts_per_page || acadprof_rest_obj._order || acadprof_rest_obj._tag ) {
    rest_ep_path += `?`;
}

// include featured image object to response
if ( acadprof_rest_obj._show_thumbnail ) {
    if ( acadprof_rest_obj._posts_per_page || acadprof_rest_obj._order || acadprof_rest_obj._tag ) {
        rest_ep_path += `_embed&`;
    } else {
        rest_ep_path += `_embed`;
    }
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
                // just checking
                // console.log( respPostData );
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
 * 
 * @param {object} settings Object containing properties that 
 * are to be destructured to assign params to construct post blocks
 */
function showPostBlocks( settings = {} ) {
    /**
     * Assignment of variables by obj destructuring
     * @param {array} data_arr Array of response data
     * @param {object} blockElem Block element to display posts
     * @param {array} exclude_ids Array containing post ids to exclude
     * @param {number} columns_lg Number of columns for large devices
     * @param {number} columns_md Number of columns for medium devices
     * @param {number} columns_sm Number of columns for small devices
     * @param {boolean} show_thumbnail Whether to display thumbnail
     * @param {string} img_size_name Thumbnail size name to display
     * @param {boolean} show_excerpt Whether to display excerpt
     */
    const { 
        data_arr, 
        blockElem, 
        btn_text, 
        exclude_ids, 
        columns_lg, 
        columns_md, 
        columns_sm, 
        show_thumbnail, 
        img_size_name, 
        show_excerpt 
    }= settings;

    let htmlOut = '';

    /**
     * Allows to determine postfix for bootstrap column class
     * @param {string/number} cols Number of columns per row
     * @returns number/boolean Returns postfix to use for bootstrap column class
     */
    const get_postfix = cols => {
        let parsed_cols_num = parseInt( cols, 10 );
        if ( typeof parsed_cols_num === 'number' && ( parsed_cols_num > 0 && parsed_cols_num <= 12 ) ) {
            return parseInt( 12 / parsed_cols_num, 10 );
        } else {
            return false;
        }
    };

    /**
     * Allows to pull featured image from post object data
     * @param {object} media_settings Object consisting to setup 
     * featured image for each post
     * @returns html/boolean False if no featured image, or gets featured 
     * image of the post
     */
    const get_featured_img = ( media_settings = {} ) => {
        // assigning by object destructuring
        const { show_img = true, img_size = 'medium', data_obj = {} } = media_settings;
        if ( ! show_img || ! data_obj['featured_media'] ) {
            return false;
        }

        if ( data_obj['_embedded']['wp:featuredmedia'][0]['media_type'] === 'image' ) {
            const data_path = data_obj['_embedded']['wp:featuredmedia'][0]['media_details']['sizes'][img_size];
            const img_alt = data_obj.title.rendered;
            const link_to = data_obj.link;
            const img_src = data_path.source_url;
            const img_w = data_path.width;
            const img_h = data_path.height;
            const img_class = 'img-fluid d-block mb-2';

            // returning img
            return `
                <a href="${link_to}" aria-hidden="true" tabindex="-1">
                    <img width="${img_w}" height="${img_h}" src="${img_src}" class="${img_class}" alt="${img_alt}" sizes="(max-width: ${img_w}px) 100vw, ${img_w}px">
                </a>
            `;
        }

    };
    
    // preparing bootstrap column class
    let col_class = 'col-12';
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

    /**
     * Constructing html for each post block
     * to be displayed
     */
    data_arr.forEach(postItem => {
        // media_settings 
        const media_settings = {
            show_img: show_thumbnail, 
            img_size: img_size_name, 
            data_obj: postItem
        };
        if ( exclude_ids.indexOf( postItem.id ) === -1 ) {
            htmlOut += `
                <div class="${col_class}">
                    ${ get_featured_img( media_settings ) ? get_featured_img( media_settings ) : `` }
                    <h4><a href="${postItem.link}" rel="bookmark">${postItem.title.rendered}</a></h4>
                    ${ show_excerpt ? postItem.excerpt.rendered : `` }
                </div>
            `;
        }
    });
    // bind posts html to block element
    if ( isDomElement( blockElem ) ) {
        blockElem.innerHTML = htmlOut;
    }
}