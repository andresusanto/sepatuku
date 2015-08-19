<?php

/**
 * Request handling related helper functions
 */
class Helper_Request {
  /**
   * Check whether current request is an ajax request or not
   * @return boolean true if current request is an ajax request, false otherwise
   */
  static function is_ajax($ctrl) {
    $http_req_with = $ctrl->getRequest('server','HTTP_X_REQUESTED_WITH');
    return isset($http_req_with) and (strtolower($http_req_with) == 'xmlhttprequest');
  }
}
