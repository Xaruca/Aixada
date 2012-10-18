delimiter |


/**
 * returns all orderable dates, irrespective if they have ordered items or not. 
 *
 */
drop procedure if exists get_orderable_dates|
create procedure get_orderable_dates(in from_date date, in the_limit int)
begin
	declare from_date_onward date default from_date;
	
  	if from_date = 0 then 
  		set from_date_onward = date(sysdate()); 
  	end if;
		
	set @q = concat("select distinct
		po.date_for_order
	from 
		aixada_product_orderable_for_date po
	where
		po.date_for_order > '", from_date_onward,"'
	order by
		po.date_for_order asc
	limit ", the_limit , ";");

	prepare st from @q;
  	execute st;
  	deallocate prepare st;
end|



/**
 * returns dates that have unvalidated shopping carts. 
 */
drop procedure if exists dates_with_unvalidated_shop_carts|
create procedure dates_with_unvalidated_shop_carts ()
begin
  select distinct 
  	date_for_shop as date_for_validation
  from 
  	aixada_cart 
  where 
  	ts_validated = 0
  order by 
  	date_for_shop desc;
end|



delimiter ;