import React,{ useState,useEffect } from 'react';

import axios from 'axios';
import DataTable from 'react-data-table-component';
import {domain} from './config';

export default function FormList() {

    const columns = [
        {
            name: 'Full Name',
            selector: row => row.id,
        },
        {
            name: 'E-mail',
            selector: row => row.email,
        },
        {
            name: 'phone',
            selector: row => row.phone,
        },
        {
            name: 'Address',
            selector: row => row.address,
        },
    ];



        const [data, setData] = useState([]);
        const [loading, setLoading] = useState(false);
        const [totalRows, setTotalRows] = useState(0);
        const [perPage, setPerPage] = useState(10);
        const fetchUsers = async page => {
            setLoading(true);
            const response = await axios.get(domain+`/backend/api/listCustomer?page=${page}&per_page=${perPage}&delay=1`);
            setData(response.data.data);
            setTotalRows(response.data.total);
            setLoading(false);
        };
        const handlePageChange = page => {
            fetchUsers(page);
        };

        const handlePerRowsChange = async (newPerPage, page) => {
            setLoading(true);
            const response = await axios.get(domain+`/backend/api/listCustomer?page=${page}&per_page=${newPerPage}&delay=1`);
            setData(response.data.data);
            setPerPage(newPerPage);
            setLoading(false);
        };

        useEffect(() => {
            fetchUsers(1); // fetch page 1 of users

        }, []);


        return (
            <div className="container pt-5">
                <div className="row">
                    <div className="col-12 mb-4 ">
                        <a href="/" className="btn btn-primary float-end"> Form </a>
                    </div>
                    <div className="col-12">
                        <DataTable
                            title="Customers"
                            columns={columns}
                            data={data}
                            progressPending={loading}
                            pagination
                            paginationServer
                            paginationTotalRows={totalRows}
                            onChangeRowsPerPage={handlePerRowsChange}
                            onChangePage={handlePageChange}
                        />
                    </div>
                </div>
            </div>
        )

}
