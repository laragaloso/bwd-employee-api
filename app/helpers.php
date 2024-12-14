<?php

if (!function_exists('response_xml')) {
    /**
     * Return a response in XML format.
     *
     * @param  mixed  $data
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    function response_xml($data)
    {
        // Create a new SimpleXMLElement instance
        $xml = new \SimpleXMLElement('<employees/>');

        // Check if the data is an array (multiple employees) or a single employee
        if (is_array($data) || is_object($data)) {
            // If it's an array of employees, loop through each employee
            foreach ($data as $employee) {
                $employeeXml = $xml->addChild('employee');

                // Convert the employee data to an array and loop through each key-value pair
                $employeeArray = is_object($employee) ? $employee->toArray() : (array)$employee;
                foreach ($employeeArray as $key => $value) {
                    // Handle null values by adding an empty tag
                    if (is_null($value)) {
                        $employeeXml->addChild($key, ''); // Create an empty tag for null values
                    } else {
                        $employeeXml->addChild($key, htmlspecialchars($value)); // Add the value as a child
                    }
                }
            }
        } else {
            // For a single employee, wrap it in a single <employee> tag
            $employeeXml = $xml->addChild('employee');
            $employeeArray = is_object($data) ? $data->toArray() : (array)$data;
            foreach ($employeeArray as $key => $value) {
                if (is_null($value)) {
                    $employeeXml->addChild($key, ''); // Create an empty tag for null values
                } else {
                    $employeeXml->addChild($key, htmlspecialchars($value)); // Add the value as a child
                }
            }
        }

        // Return the XML response
        return response($xml->asXML(), 200)->header('Content-Type', 'application/xml');
    }
}

if (!function_exists('response_json')) {
    /**
     * Return a response in JSON format.
     *
     * @param  mixed  $data
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    function response_json($data)
    {
        // If the data is a collection of employees (array or object), convert to array
        if (is_array($data) || is_object($data)) {
            // Convert the data to an array if it's an object (model or collection)
            $data = array_map(function($employee) {
                return is_object($employee) ? $employee->toArray() : (array)$employee;
            }, $data);
        } else {
            // If it's a single employee, convert it to an object (not an array)
            $data = is_object($data) ? $data->toArray() : (array)$data;
        }
        
        // Return the JSON response
        return response()->json($data, 200);
    }
}

?>
