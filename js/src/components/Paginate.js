import React, {Component} from 'react';

class Paginate extends Component {

    state = {
        start: 1,
        currentPage: this.props.pagingInfo.currentPage || 1,
        itemsPerPage: this.props.pagingInfo.itemsPerPage
    };

    length = this.props.options.length;

    componentDidUpdate(prevProps, prevState) {
        if (this.props.pagingInfo.currentPage !== null && this.props.pagingInfo.currentPage !== this.state.currentPage)
            this.setState({
                currentPage: this.props.pagingInfo.currentPage,
                start: 1
            });

        if (prevState.itemsPerPage !== this.state.itemsPerPage)
            this.props.onChangeItemsPerPage(this.state.itemsPerPage);
    }

    render() {
        let pages = [];
        const {currentPage} = this.state;
        const totalPages = this.getTotalPages();
        for (let i = this.state.start; i <= this.getEnd() && this.getTotalPages(); i++)
            pages[i] =
                <li className={currentPage === i ? 'active' : ''}>
                    {/*Замыкание в данном случае не обязательно так как счетчик i обевлен как let*/}
                    <a href="#" onClick={(e)=>((e,i) =>{ this.onClick(e,i) }).call(this,e,i)}>{i}</a>
                </li>;

        return(
            <div className={'text-right'}>
                <nav aria-label="Page navigation">
                    <ul className="pagination">
                        <li className={currentPage === 1 ? 'disabled' : ''}>
                            <a href="#" onClick={(e) => { this.onClick.call(this, e, currentPage - 1) }} aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        { pages }
                        <li className={currentPage === totalPages ? 'disabled' : ''}>
                            <a href="#" onClick={(e) => { this.onClick.call(this, e, currentPage + 1) }} aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        );
    }

    getTotalPages() {
        return Math.ceil(this.props.pagingInfo.totalItems / this.state.itemsPerPage);
    }

    getEnd() {
        const end = this.state.start + this.length - 1;
        return Math.floor(this.getTotalPages() / end) ? end : this.getTotalPages();
    }

    onClick(event, page) {
        event.preventDefault();
        const totalPages = this.getTotalPages();
        if (page === this.state.currentPage || page < 1 || page > totalPages)
            return false;
        this.setState({
            currentPage: page
        });
        if (this.isEnd(page) && totalPages % page || this.isStart(page) && page ^ 1)
            this.setState({
                start: this.nextStart(page),
            });

        this.props.onChangeCurrentPage(page);
    }

    isEnd(page) {
        return page === this.getEnd();
    }

    isStart(page) {
        return page === this.state.start;
    }

    nextStart(page) {

        const shift = Math.floor(this.length / 2);
        const preStart = page - shift;
        return preStart < 1 ? 1 : (preStart + this.length > this.getTotalPages() ? this.getTotalPages() - this.length + 1 : preStart);
    }
}

export default Paginate;