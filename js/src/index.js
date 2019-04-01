import React from 'react'
import {render} from 'react-dom'
import BookCatalogApp from './components/BookCatalogApp'

render(<BookCatalogApp itemsPerPage={6} />, document.getElementById("book-catalog-app"));